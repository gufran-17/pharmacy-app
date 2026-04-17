output "ecr_repository_url" {
  description = "ECR Repository URL"
  value       = aws_ecr_repository.pharmacy_repo.repository_url
}