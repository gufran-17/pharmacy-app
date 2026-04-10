pipeline {
    agent any

    triggers {
        pollSCM('* * * * *')
    }

    environment {
        AWS_REGION = "ap-south-1"
        ECR_REPO = "062000001375.dkr.ecr.ap-south-1.amazonaws.com/pharmacy-app-repo"
        CLUSTER_NAME = "pharmacy-eks"
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
                sh 'docker build -t pharmacy-app .'
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
                docker login --username AWS --password-stdin 062000001375.dkr.ecr.ap-south-1.amazonaws.com
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
                sh '''
                aws eks update-kubeconfig --region $AWS_REGION --name $CLUSTER_NAME
                '''
            }
        }

        stage('Deploy to EKS') {
            steps {
                sh '''
                sed -i "s|image:.*|image: $ECR_REPO:$IMAGE_TAG|g" kubernetes/deployment.yaml
                kubectl apply -f kubernetes/deployment.yaml
                kubectl apply -f kubernetes/service.yaml
                '''
            }
        }
    }
}