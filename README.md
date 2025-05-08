# grievance_redressal_system
A full-stack web application for citizens to submit grievances and for administrators to manage and resolve them.

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


grievance-redressal-system/
│
├── backend/                    # PHP API
│   ├── api/
│   │   ├── submit_complaint.php
│   │   ├── update_status.php
│   │   └── fetch_report.php
│   ├── config/
│   │   └── db.php
│   └── Dockerfile
│
├── frontend/                   # HTML, Bootstrap, JS, JQuery
│   ├── index.html
│   ├── login.html
│   ├── dashboard.html
│   └── assets/
│       ├── css/
│       └── js/
│
├── database/
│   ├── schema.sql
│   └── init.sql
│
├── docker-compose.yml         # For Dev Deployment
├── jenkins/
│   └── Jenkinsfile
├── kubernetes/
│   ├── deployment.yaml
│   ├── service.yaml
│   └── helm-chart/
│
├── monitoring/
│   ├── grafana/
│   ├── prometheus/
│   └── docker-compose.yml
│
├── README.md
└── docs/
    ├── deployment_architecture.png
    └── project_documentation.md