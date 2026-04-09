# ---------------------------
# Fetch Existing VPC
# ---------------------------
data "aws_vpc" "existing_vpc" {
  filter {
    name   = "tag:Name"
    values = ["pharmacy-vpc"]
  }
}

# ---------------------------
# Fetch Existing Subnets
# ---------------------------
data "aws_subnet" "subnet_1" {
  filter {
    name   = "tag:Name"
    values = ["pharmacy-public-subnet"]
  }
}

data "aws_subnet" "subnet_2" {
  filter {
    name   = "tag:Name"
    values = ["pharmacy-public-subnet-2"]
  }
}

# ---------------------------
# Fetch Existing EC2 Security Group
# ---------------------------
data "aws_security_group" "ec2_sg" {
  filter {
    name   = "group-name"
    values = ["pharmacy-sg"]
  }
}

# ---------------------------
# DB Subnet Group
# ---------------------------
resource "aws_db_subnet_group" "pharmacy_db_subnet_group" {
  name = "pharmacy-db-subnet-group"

  subnet_ids = [
    data.aws_subnet.subnet_1.id,
    data.aws_subnet.subnet_2.id
  ]

  tags = {
    Name = "pharmacy-db-subnet-group"
  }
}

# ---------------------------
# RDS Security Group
# ---------------------------
resource "aws_security_group" "rds_sg" {
  name        = "pharmacy-rds-sg"
  description = "Allow MySQL from EC2"
  vpc_id      = data.aws_vpc.existing_vpc.id

  ingress {
    from_port       = 3306
    to_port         = 3306
    protocol        = "tcp"

    security_groups = [data.aws_security_group.ec2_sg.id]
  }

  egress {
    from_port   = 0
    to_port     = 0
    protocol    = "-1"
    cidr_blocks = ["0.0.0.0/0"]
  }
}

# ---------------------------
# RDS Instance
# ---------------------------
resource "aws_db_instance" "pharmacy_db" {
  identifier = "pharmacy-db"

  engine         = "mysql"
  instance_class = "db.t3.micro"

  allocated_storage = 20

  db_name  = "pharmacy_mgmt"
  username = var.db_username
  password = var.db_password

  publicly_accessible = false
  multi_az            = false

  vpc_security_group_ids = [aws_security_group.rds_sg.id]
  db_subnet_group_name   = aws_db_subnet_group.pharmacy_db_subnet_group.name

  skip_final_snapshot = true
}