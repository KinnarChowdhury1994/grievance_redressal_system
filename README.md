# grievance_redressal_system
A full-stack web application for citizens to submit grievances and for administrators to manage and resolve them.

#### 🔧 Project Overview
A web-based complaint management system where:
- Citizens can submit complaints.
- Admins can view, update statuses, and act on complaints.

# Grievance Redressal System - Generic

A full-stack web application for citizens to submit grievances and for administrators to manage and resolve them.

## Table of Contents

- [Project Overview](#project-overview)
- [Features](#features)
- [Tech Stack](#tech-stack)
- [Getting Started](#getting-started)
  - [Development Setup (Docker)](#development-setup-docker)
  - [Production Deployment (Kubernetes)](#production-deployment-kubernetes)
- [API Endpoints](#api-endpoints)
- [Database Schema](#database-schema)
- [CI/CD](#ci-cd)
- [Monitoring](#monitoring)
- [Usage](#usage)
  - [For Citizens](#for-citizens)
  - [For Administrators](#for-administrators)
- [Contributing](#contributing)
- [License](#license)

## Project Overview

This project aims to provide a user-friendly platform for citizens of West Bengal to submit their complaints and for government administrators to efficiently manage, track, and resolve these grievances.

## Features

**Citizens:**

-   Easy-to-use complaint submission form.
-   (Optional: View their submitted complaint history and status).

**Administrators:**

-   Secure login.
-   Dashboard to view all submitted complaints.
-   Ability to filter and sort complaints by status.
-   Option to update the status of a complaint (e.g., Pending, In Progress, Resolved).
-   Field to add details of the action taken.

## Tech Stack

**Frontend:**

-   HTML5
-   CSS3
-   Bootstrap 4/5
-   JavaScript
-   jQuery
-   AJAX

**Backend:**

-   PHP

**Database:**

-   MySQL

**Containerization:**

-   Docker

**Orchestration:**

-   Kubernetes
-   Helm

**CI/CD:**

-   Jenkins

**Web Server:**

-   Apache or Nginx (configured within Docker)

# Deployment Architecture for Grievance Redressal System

This document outlines the deployment architecture for the Grievance Redressal System, detailing how the application components are organized, interact, and are managed in a production environment. This architecture emphasizes scalability, high availability, and maintainability using containerization and orchestration technologies.

## Overview

The system is designed as a full-stack web application, allowing citizens to submit grievances and administrators to manage them. The architecture utilizes a microservices-inspired approach within a containerized environment managed by Kubernetes.

## Key Components

1.  **Client (Citizen/Admin Browser):**
    * This is the user interface accessed by both citizens and administrators through their web browsers. It interacts with the backend services via standard HTTP/HTTPS protocols.

2.  **Load Balancer:**
    * **Role:** Acts as the single entry point for all incoming traffic to the application. It distributes these requests across multiple instances of the Web Servers.
    * **Benefits:**
        * **Scalability:** Distributes workload to handle a larger number of concurrent users.
        * **High Availability:** If one Web Server instance becomes unavailable, traffic is automatically routed to healthy instances, ensuring continuous service.

3.  **Web Servers (Apache/Nginx):**
    * **Role:** Primarily responsible for serving static content (HTML, CSS, JavaScript files) to the clients. They also act as reverse proxies, forwarding dynamic requests (PHP execution) to the Application Servers.
    * **Technology:** Can be either Apache HTTP Server or Nginx, chosen for their performance and reliability in serving web content.

4.  **Application Servers (PHP-FPM with CodeIgniter):**
    * **Role:** These servers host the core application logic, built using the CodeIgniter PHP framework. They handle:
        * Processing user requests.
        * Implementing business logic.
        * Interacting with the MySQL database to store and retrieve data.
        * Generating dynamic web pages or API responses (typically in JSON format for AJAX interactions).
    * **Technology:** PHP-FPM (FastCGI Process Manager) is used for efficient management of PHP processes, and CodeIgniter provides a structured framework for application development. Running multiple instances ensures the application can handle a higher volume of concurrent operations.

5.  **MySQL (Dockerized in Kubernetes StatefulSet):**
    * **Role:** The relational database responsible for persistent storage of all application data, including user accounts, complaint details, and status updates.
    * **Technology:** MySQL is used for its reliability and features.
    * **Containerization (Docker):** Running MySQL within a Docker container simplifies deployment, management, and ensures consistency across different environments.
    * **Kubernetes StatefulSet:** This Kubernetes workload resource is specifically designed for managing stateful applications like databases. It provides:
        * **Stable Network Identities:** Each MySQL pod has a consistent hostname and IP address, crucial for database replication and consistency.
        * **Persistent Storage:** Data is stored on Persistent Volumes, ensuring data survives pod restarts or rescheduling.
        * **Ordered, Graceful Deployment and Scaling:** Kubernetes manages the deployment and scaling of MySQL pods in a predictable order, which is important for maintaining data integrity.

6.  **Kubernetes Cluster:**
    * **Role:** The central orchestration platform that automates the deployment, scaling, and management of the containerized application components. It provides a resilient and scalable infrastructure.
    * **Key Kubernetes Resources:**
        * **Nodes:** The worker machines (physical or virtual) where the containers (pods) run.
        * **Pods:** The smallest deployable units in Kubernetes, typically containing one or more tightly coupled containers (e.g., a PHP-FPM container and potentially sidecar containers).
        * **Deployments:** Manage stateless application pods (Web Servers, Application Servers), ensuring a specified number of replicas are running and facilitating rolling updates.
        * **Services:** Provide a stable abstraction layer to access pods. They offer a consistent IP address and DNS name for a set of pods, enabling load balancing within the cluster.
        * **StatefulSet:** Manages the stateful MySQL database pods, as described above.
        * **Ingress:** Manages external access to the services within the cluster, typically handling HTTP and HTTPS routing, load balancing, and SSL termination.
        * **PersistentVolumeClaims (PVCs):** Requests for persistent storage that are provisioned by Persistent Volumes (PVs), used here to ensure the MySQL database data is persistent.

7.  **Ingress:**
    * **Role:** Acts as the entry point for external HTTP(S) traffic into the Kubernetes cluster. It routes traffic to the appropriate Services based on rules defined in Ingress resources (e.g., hostnames, paths).
    * **Functionality:**
        * **External Access:** Exposes HTTP and HTTPS routes from outside the cluster to Services within the cluster.
        * **Load Balancing:** Can perform basic load balancing across the backend pods of a Service.
        * **SSL Termination:** Can handle SSL/TLS certificates (e.g., from Let's Encrypt) to secure connections.
        * **Name-based Virtual Hosting:** Allows routing traffic to different services based on the hostname in the HTTP request.

8.  **Monitoring (Grafana & Prometheus):**
    * **Role:** Essential for observing the health, performance, and resource utilization of the application and infrastructure.
    * **Prometheus:** A time-series database that collects and stores metrics from the Kubernetes cluster, Docker containers, and applications. It scrapes metrics from configured endpoints.
    * **Grafana:** A powerful data visualization tool that connects to Prometheus (and other data sources) to create informative dashboards, allowing for real-time monitoring, historical analysis, and alerting.

## Data Flow

1.  A user (citizen or administrator) sends a request to the application's domain name.
2.  The DNS system resolves the domain name to the IP address of the Load Balancer or Ingress controller.
3.  The Load Balancer/Ingress routes the incoming request to one of the available Web Server pods based on configured rules and load balancing algorithms.
4.  If the request is for static content, the Web Server serves it directly.
5.  If the request requires dynamic processing (e.g., submitting a complaint, logging in), the Web Server forwards the request to one of the Application Server pods.
6.  The CodeIgniter application on the Application Server processes the request, potentially interacting with the MySQL database (running as a StatefulSet) to read or write data.
7.  The Application Server generates a response (HTML page or API data).
8.  The response travels back through the Web Server, Load Balancer/Ingress, and finally to the user's browser.
9.  In the background, Prometheus periodically scrapes metrics from the Web Servers, Application Servers, MySQL pods, and the Kubernetes infrastructure itself.
10. Grafana queries Prometheus to visualize these metrics on dashboards, providing insights into the system's performance and health.

## Scalability and High Availability

* **Scalability:** The number of Web Server and Application Server pods can be easily scaled up or down based on traffic demands using Kubernetes scaling features (Horizontal Pod Autoscaler). The Load Balancer distributes traffic across these instances. MySQL scaling can be more complex with StatefulSets and might involve techniques like replication and clustering.
* **High Availability:** Running multiple replicas of the Web Servers and Application Servers ensures that the application remains available even if some instances fail. Kubernetes automatically restarts failed pods. The Load Balancer directs traffic only to healthy instances. The MySQL StatefulSet with persistent volumes ensures data availability.

## Benefits of this Architecture

* **Scalability:** Easily handle increasing user loads by scaling the application components.
* **High Availability:** Ensures continuous service availability through redundancy and automated recovery.
* **Maintainability:** Containerization provides a consistent and isolated environment for each component, simplifying deployments and updates. Kubernetes automates many operational tasks.
* **Resource Efficiency:** Kubernetes optimizes resource utilization by efficiently scheduling and managing containers across the cluster.
* **Portability:** Containerized applications can be easily deployed across different environments (development, staging, production).
* **Modularity:** Separating the web serving and application logic into different containers promotes a more organized and maintainable codebase.

This architecture provides a robust and scalable foundation for the Grievance Redressal System, capable of handling the demands of a government-level application while ensuring reliability and ease of management.

<!-- 
cd frontend
docker build -t grievance-frontend .
docker run -d -p 8080:80 --name frontend grievance-frontend

helm install grievance-system ./grievance-helm-chart

 -->

# Grievance Redressal System

## Overview

This is a full-stack grievance redressal system built with:

- **Frontend**: HTML5, Bootstrap, JS, jQuery, AJAX
- **Backend**: PHP (MySQL for DB)
- **DevOps**: Docker, Kubernetes (Helm), Jenkins for CI/CD
- **Monitoring**: Prometheus, Grafana

## Features

- Users can submit complaints and track their status.
- Admin can update complaint statuses.
- Real-time reporting using Grafana dashboards.

## Project Structure

- `backend/`: PHP API for grievance management.
- `frontend/`: HTML, JS, and Bootstrap for user interface.
- `database/`: MySQL schema and initialization files.
- `monitoring/`: Prometheus and Grafana configuration for monitoring.
- `kubernetes/`: Kubernetes deployment and Helm chart.
- `jenkins/`: Jenkins pipeline for CI/CD.

## Getting Started

### Prerequisites

- Docker
- Docker Compose
- Kubernetes
- Helm
- Jenkins

### Local Development Setup

1. Clone the repository:
   ```bash
   git clone https://github.com/your-repository/grievance-redressal-system.git
   cd grievance-redressal-system



### 2. **Deployment Architecture Diagram** (`deployment_architecture.png`)

- **Frontend** communicates with **Backend (PHP)** through AJAX calls.
- **Backend** connects to **MySQL Database**.
- **Docker** containers for local development.
- **Jenkins** for CI/CD, building and deploying Docker containers.
- **Kubernetes** for scalable production deployment, with **Helm** charts for automation.
- **Prometheus** collects metrics, and **Grafana** visualizes them.

This can be done using a diagram tool like [Lucidchart](https://www.lucidchart.com) or [draw.io](https://app.diagrams.net).

### 3. **Setup the Backend (PHP & MySQL)**

The backend consists of PHP scripts for grievance management and a MySQL database.
Build and start the backend using Docker:
```bash
docker-compose up --build backend
```

### **MySQL Setup**
The database schema and initialization files are located in the database/ directory. They are automatically loaded when the containers are built.

### **Setup the Frontend (HTML, Bootstrap, JavaScript)**
The frontend is served via Apache/Nginx, which is configured in the Docker setup.
Build and start the frontend:
```bash
docker-compose up --build frontend
```

### **Setup Monitoring with Grafana and Prometheus**
To monitor system health and application metrics, set up Grafana and Prometheus.
Build and start the monitoring services:
```bash
cd monitoring/
docker-compose up --build
```

**Access Grafana at: http://localhost:3000**
 - Username: admin
 - Password: admin

**Access Prometheus at: http://localhost:9090**
 - Note: Ensure the correct URL for Prometheus is configured in Grafana’s data source.


### **CI/CD Pipeline Setup with Jenkins**

Jenkins is set up to handle Continuous Integration and Continuous Deployment (CI/CD).
Install Jenkins: Follow the installation guide for Jenkins on your platform: https://www.jenkins.io/doc/book/installing/

Jenkins Pipeline Configuration:

 - Open Jenkins and create a new Pipeline job.
 - In the job configuration, point to the Jenkinsfile located in the jenkins/ directory.
 - Ensure Jenkins has access to Docker and Kubernetes.


### **Build and Deploy**
The Jenkins pipeline will automatically build and deploy the application containers. It will:
Build Docker images.
Push the images to a container registry (DockerHub, AWS ECR, etc.).
Deploy to a Kubernetes cluster.
**Production Setup with Kubernetes and Helm:**
The application can be deployed to a scalable environment using Kubernetes and Helm.
Kubernetes Cluster Setup: Ensure your Kubernetes cluster is set up and configured. You can use Minikube for local testing or a managed service like AWS EKS or Google GKE for production.
**Helm Setup:**
Install Helm on your system: https://helm.sh/docs/intro/install/
**Deploy Using Helm:**
Navigate to the kubernetes/helm-chart/ directory.
Use Helm to install the chart in your cluster:
```bash
helm install grievance-redressal ./helm-chart
```
This will deploy the application to the Kubernetes cluster, using the configurations in deployment.yaml and service.yaml.

### **Monitoring Setup**

**Prometheus:** Collects metrics about the system. Prometheus is configured to scrape metrics from the backend and MySQL.

**Grafana:** Visualizes the data collected by Prometheus.

After starting Grafana, configure the Prometheus data source and import the dashboards to view the metrics.

Accessing the Application

#### Frontend: Open the browser and go to http://localhost:8080 to access the grievance redressal system.

#### Grafana Dashboard: Access it at http://localhost:3000 to monitor system performance and metrics.

#### Prometheus: Access it at http://localhost:9090 for querying metrics directly.

## Production Deployment

**Build the Docker Images:**

Use the following command to build the Docker images for the frontend, backend, and monitoring services:
```bash
docker-compose build
```
 - Push the Images to a Container Registry:
 - Once the images are built, push them to a container registry (e.g., DockerHub):

```bash
docker tag backend your-dockerhub-username/grievance-backend
docker push your-dockerhub-username/grievance-backend
```

**Kubernetes Setup:**
Deploy the system using Helm or kubectl:

Ensure Helm is set up on your Kubernetes cluster.

Use the helm install command to deploy the Helm chart.

**SSL (Let's Encrypt):**
If deploying to production, configure SSL with Let's Encrypt. You can use tools like Certbot to automate SSL certificate generation for your domain.

---

This should complete the remaining sections of your project! Let me know if you need more details or further help with the implementation.
