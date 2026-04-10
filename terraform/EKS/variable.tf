variable "cluster_name" {
  default = "pharmacy-eks"
}

variable "vpc_id" {
  default = "vpc-0b730b4a09e9c1de1"
}

variable "subnet_ids" {
  default = [
    "subnet-05b3eb240ded9ea82",
    "subnet-025ac6e693b50b5e1"
  ]
}