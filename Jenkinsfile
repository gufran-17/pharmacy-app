pipeline {
    agent any

    triggers {
        pollSCM('* * * * *')
    }

    environment {
        AWS_REGION   = "ap-south-1"
        ECR_REPO     = "062000001375.dkr.ecr.ap-south-1.amazonaws.com/pharmacy-app-repo"
        CLUSTER_NAME = "pharmacy-eks"
        NAMESPACE    = "pharmacy"
    }

    stages {

        stage('Clone Code') {
            steps {
                git branch: 'main', url: 'https://github.com/gufran-17/pharmacy-app.git'
            }
        }

        stage('Generate Version Tag') {
            steps {
                script {
                    env.IMAGE_TAG = "v${BUILD_NUMBER}"
                }
            }
        }

        stage('Build Docker Image') {
            steps {
                sh 'docker build --no-cache -t pharmacy-app .'
            }
        }

        stage('Tag Image') {
            steps {
                sh 'docker tag pharmacy-app:latest $ECR_REPO:$IMAGE_TAG'
            }
        }

        stage('Login to ECR') {
            steps {
                sh '''
                    aws ecr get-login-password --region $AWS_REGION | \
                    docker login --username AWS --password-stdin $ECR_REPO
                '''
            }
        }

        stage('Push to ECR') {
            steps {
                sh 'docker push $ECR_REPO:$IMAGE_TAG'
            }
        }

        stage('Update Kubeconfig') {
            steps {
                sh 'aws eks update-kubeconfig --region $AWS_REGION --name $CLUSTER_NAME'
            }
        }

        stage('Deploy to EKS') {
            steps {
                sh '''
                    # Apply namespace + service (these don't change often)
                    kubectl apply -f kubernetes/deployment.yaml
                    kubectl apply -f kubernetes/service.yaml

                    # Update ONLY the image — no sed, no YAML corruption
                    kubectl set image deployment/pharmacy-app \
                        pharmacy=$ECR_REPO:$IMAGE_TAG \
                        -n $NAMESPACE

                    # Wait for rollout to finish (fails build if deploy fails)
                    kubectl rollout status deployment/pharmacy-app \
                        -n $NAMESPACE \
                        --timeout=380s
                '''
            }
        }

        stage('Get App URL') {
            steps {
                sh '''
                    echo "============================================"
                    echo "Waiting for LoadBalancer URL..."
                    sleep 10
                    kubectl get svc pharmacy-service -n $NAMESPACE
                    echo "============================================"
                '''
            }
        }
    }

    post {
        success {
            echo "SUCCESS: Build $IMAGE_TAG deployed to EKS"
        }
        failure {
            echo "FAILED: Rolling back to previous version..."
            sh '''
                kubectl rollout undo deployment/pharmacy-app \
                    -n pharmacy || true
            '''
        }
    }
}
