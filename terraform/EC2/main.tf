data "aws_ami" "amazon_linux" {
  most_recent = true

  owners = ["amazon"]

  filter {
    name   = "name"
    values = ["amzn2-ami-hvm-*-x86_64-gp2"]
  }
}

# ---------------------------
# VPC
# ---------------------------
resource "aws_vpc" "pharmacy_vpc" {
  cidr_block           = var.vpc_cidr
  enable_dns_support   = true
  enable_dns_hostnames = true

  tags = {
    Name = "pharmacy-vpc"
  }
}

# ---------------------------
# Internet Gateway
# ---------------------------
resource "aws_internet_gateway" "pharmacy_igw" {
  vpc_id = aws_vpc.pharmacy_vpc.id

  tags = {
    Name = "pharmacy-igw"
  }
}

# ---------------------------
# Public Subnet
# ---------------------------
resource "aws_subnet" "pharmacy_public_subnet" {
  vpc_id                  = aws_vpc.pharmacy_vpc.id
  cidr_block              = var.public_subnet_cidr
  availability_zone       = "${var.aws_region}a"
  map_public_ip_on_launch = true

  tags = {
    Name = "pharmacy-public-subnet"
  }
}

resource "aws_subnet" "pharmacy_public_subnet_2" {
  vpc_id                  = aws_vpc.pharmacy_vpc.id
  cidr_block              = var.public_subnet_2_cidr
  availability_zone       = "${var.aws_region}b"
  map_public_ip_on_launch = true

  tags = {
    Name = "pharmacy-public-subnet-2"
  }
}

# ---------------------------
# Route Table
# ---------------------------
resource "aws_route_table" "pharmacy_rt" {
  vpc_id = aws_vpc.pharmacy_vpc.id

  tags = {
    Name = "pharmacy-rt"
  }
}

resource "aws_route_table" "pharmacy_rt_2" {
  vpc_id = aws_vpc.pharmacy_vpc.id

  tags = {
    Name = "pharmacy-rt-2"
  }
}

resource "aws_route" "internet_access" {
  route_table_id         = aws_route_table.pharmacy_rt.id
  destination_cidr_block = "0.0.0.0/0"
  gateway_id             = aws_internet_gateway.pharmacy_igw.id
}

resource "aws_route" "internet_access_2" {
  route_table_id         = aws_route_table.pharmacy_rt_2.id
  destination_cidr_block = "0.0.0.0/0"
  gateway_id             = aws_internet_gateway.pharmacy_igw.id
}

resource "aws_route_table_association" "public_assoc" {
  subnet_id      = aws_subnet.pharmacy_public_subnet.id
  route_table_id = aws_route_table.pharmacy_rt.id
}

resource "aws_route_table_association" "public_assoc_2" {
  subnet_id      = aws_subnet.pharmacy_public_subnet_2.id
  route_table_id = aws_route_table.pharmacy_rt_2.id
}

# ---------------------------
# Key Pair (Auto create)
# ---------------------------
resource "tls_private_key" "ec2_key" {
  algorithm = "RSA"
  rsa_bits  = 4096
}

resource "aws_key_pair" "ec2_keypair" {
  key_name   = "pharmacy-key"
  public_key = tls_private_key.ec2_key.public_key_openssh
}

resource "local_file" "private_key_pem" {
  filename        = "${path.module}/pharmacy-key.pem"
  content         = tls_private_key.ec2_key.private_key_pem
  file_permission = "0400"
}

# ---------------------------
# Security Group
# ---------------------------
resource "aws_security_group" "pharmacy_sg" {
  name        = "pharmacy-sg"
  description = "Allow SSH, HTTP, App Ports"
  vpc_id      = aws_vpc.pharmacy_vpc.id

  ingress {
    from_port   = 22
    to_port     = 22
    protocol    = "tcp"
    cidr_blocks = ["0.0.0.0/0"]
  }

  ingress {
    from_port   = 80
    to_port     = 80
    protocol    = "tcp"
    cidr_blocks = ["0.0.0.0/0"]
  }

  ingress {
    from_port   = 3000
    to_port     = 3000
    protocol    = "tcp"
    cidr_blocks = ["0.0.0.0/0"]
  }

  ingress {
  from_port   = 30000
  to_port     = 32767
  protocol    = "tcp"
  cidr_blocks = ["0.0.0.0/0"]
}

  ingress {
    from_port   = 5000
    to_port     = 5000
    protocol    = "tcp"
    cidr_blocks = ["0.0.0.0/0"]
  }

  ingress {
    from_port   = 8080
    to_port     = 8080
    protocol    = "tcp"
    cidr_blocks = ["0.0.0.0/0"]
  }

  egress {
    from_port   = 0
    to_port     = 0
    protocol    = "-1"
    cidr_blocks = ["0.0.0.0/0"]
  }

  tags = {
    Name = "pharmacy-sg"
  }
}

# ---------------------------
# EC2 Instance
# ---------------------------
resource "aws_instance" "pharmacy_ec2" {
  ami                         = data.aws_ami.amazon_linux.id
  instance_type               = var.instance_type
  subnet_id                   = aws_subnet.pharmacy_public_subnet.id
  vpc_security_group_ids      = [aws_security_group.pharmacy_sg.id]
  associate_public_ip_address = true

  key_name = aws_key_pair.ec2_keypair.key_name

  tags = {
    Name = "pharmacy-ec2"
  }
}