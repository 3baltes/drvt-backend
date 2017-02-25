# Docker Redundancy Validation Tool - Backend


This is the GitHub repository for the source code of the project accompanying my 
bachelors thesis. The main goal was to create a tool to validate redundancies in 
a docker-cluster by using the analogy of redundancies and dependencies to elements 
of the graph theory.


## Technology
As this is the backend application, it is written as a Symfony application exposing a REST-API. 
To visualize the data, a [frontend application](https://github.com/3baltes/drvt-frontend) is available.

## Installation
The application is available via [Docker](https://hub.docker.com/r/3baltes/drvt-backend/). Simply run the
following command on any docker host: 

```
docker run -d -p8081:80 --name backend 3baltes/drvt-backend
```
   
   
