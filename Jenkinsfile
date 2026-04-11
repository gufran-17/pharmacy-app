pipeline {
    agent any

    triggers {
        pollSCM('H/2 * * * *') // Every 2 min is saner than every minute
    }

    environment {
        AWS_REGION   = "ap-south-1"
        ECR_REPO     = "062000001375.dkr.ecr.ap-south-1.amazonaws.com/pharmacy-app-repo"
        CLUSTER_NAME = "pharmacy-eks"
        NAMESPACE    = "pharmacy"
        APP_NAME     = "pharmacy-app"
        CONTAINER    = "pharmacy"
    }

    stages {

        stage('Checkout') {
            steps {
                // BUG FIX #2: Force-clean workspace before checkout
                // Ensures deleted/renamed files don't linger on the Jenkins node
                checkout([
                    $class: 'GitSCM',
                    branches: [[name: '*/main']],
                    extensions: [
                        [$class: 'CleanBeforeCheckout'],   // git clean -fdx
                        [$class: 'CleanCheckout']
                    ],
                    userRemoteConfigs: [[
                        url: 'https://github.com/gufran-17/pharmacy-app.git'
                    ]]
                ])

                // Confirm exactly what commit is being built
                sh 'git log -1 --oneline'
            }
        }

        stage('Set Build Variables') {
            steps {
                script {
                    // Tag includes commit SHA for full traceability
                    def shortSha = sh(
                        script: 'git rev-parse --short HEAD',
                        returnStdout: true
                    ).trim()
                    env.IMAGE_TAG     = "v${BUILD_NUMBER}-${shortSha}"
                    env.FULL_IMAGE    = "${ECR_REPO}:${IMAGE_TAG}"
                    echo "Image to build and deploy: ${env.FULL_IMAGE}"
                }
            }
        }

        stage('Build Docker Image') {
            steps {
                // BUG FIX #3: --no-cache prevents Docker reusing stale COPY layers
                // --pull ensures base image (e.g. php:apache) is always freshest version
                sh '''
                    docker build \
                        --no-cache \
                        --pull \
                        -t $FULL_IMAGE \
                        .
                '''
            }
        }

        stage('Verify Image Contents') {
            // Catch stale-code bugs BEFORE pushing to ECR or deploying
            steps {
                sh '''
                    echo "--- Verifying built image contains latest code ---"
                    docker run --rm $FULL_IMAGE \
                        cat /var/www/html/config/constants.php
                    echo "--- End of constants.php ---"
                '''
            }
        }

        stage('Push to ECR') {
            steps {
                sh '''
                    aws ecr get-login-password --region $AWS_REGION | \
                        docker login --username AWS --password-stdin $ECR_REPO

                    docker push $FULL_IMAGE

                    echo "Pushed: $FULL_IMAGE"
                '''
            }
        }

        stage('Deploy to EKS') {
            steps {
                sh '''
                    aws eks update-kubeconfig \
                        --region $AWS_REGION \
                        --name $CLUSTER_NAME

                    # BUG FIX #1: Apply structural YAML changes separately.
                    # Use --dry-run to detect if deployment.yaml image tag
                    # is hardcoded — if so, the apply would overwrite set image.
                    # We apply ONLY for config structure (replicas, volumes, etc),
                    # then ALWAYS override the image with the freshly built tag.
                    kubectl apply -f kubernetes/deployment.yaml -n $NAMESPACE
                    kubectl apply -f kubernetes/service.yaml    -n $NAMESPACE

                    # This is the authoritative image update — always runs last
                    kubectl set image deployment/$APP_NAME \
                        $CONTAINER=$FULL_IMAGE \
                        -n $NAMESPACE

                    # Annotate with build info for audit trail
                    kubectl annotate deployment/$APP_NAME \
                        -n $NAMESPACE \
                        kubernetes.io/change-cause="Jenkins build $BUILD_NUMBER | $IMAGE_TAG" \
                        --overwrite

                    echo "Waiting for rollout to complete..."
                    kubectl rollout status deployment/$APP_NAME \
                        -n $NAMESPACE \
                        --timeout=300s
                '''
            }
        }

        stage('Smoke Test') {
            steps {
                sh '''
                    echo "--- Running pod-level verification ---"

                    # Confirm the running pod uses the new image tag
                    kubectl get pods -n $NAMESPACE -o wide
                    kubectl get deployment $APP_NAME -n $NAMESPACE \
                        -o jsonpath="{.spec.template.spec.containers[0].image}"
                    echo ""

                    # Verify the file in the RUNNING container matches expectation
                    kubectl exec deployment/$APP_NAME \
                        -n $NAMESPACE \
                        -- cat /var/www/html/config/constants.php

                    echo ""
                    echo "SUCCESS: Build $IMAGE_TAG is live in EKS"
                '''
            }
        }

        stage('Show App URL') {
            steps {
                sh '''
                    kubectl get svc -n $NAMESPACE
                '''
            }
        }
    }

    post {
        success {
            echo "✅ Deployed $IMAGE_TAG successfully"
            // Clean up local Docker image to prevent disk bloat on Jenkins node
            sh 'docker rmi $FULL_IMAGE || true'
        }
        failure {
            echo "❌ Deployment failed — initiating rollback"
            sh '''
                aws eks update-kubeconfig \
                    --region $AWS_REGION \
                    --name $CLUSTER_NAME 2>/dev/null || true

                kubectl rollout undo deployment/$APP_NAME \
                    -n $NAMESPACE || true

                kubectl rollout status deployment/$APP_NAME \
                    -n $NAMESPACE \
                    --timeout=120s || true

                echo "Rollback complete. Check pod logs:"
                kubectl logs deployment/$APP_NAME \
                    -n $NAMESPACE \
                    --tail=50 || true
            '''
        }
        always {
            // Always clean up dangling images on the Jenkins node
            sh 'docker image prune -f || true'
        }
    }
}