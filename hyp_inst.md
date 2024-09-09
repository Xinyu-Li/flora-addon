The actual hypothes.is service is deployed in AWS using Elastic Beanstalk and the scripts used for this can be found at https://github.com/hypothesis/deployment . In brief, you will need to:

1. Setup containers running Postgres 9.x, Elasticsearch 1.5/1.6 and RabbitMQ, or use hosted versions of these services (we use AWS RDS, AWS ES, CloudAMPQ respectively)
2. Build the Docker image for "h" (using `make docker`) or use the images we publish to http://hub.docker.com/r/hypothesis/hypothesis
3. Run the Docker container for h, pointing it at the Elasticsearch, Postgres and RabbitMQ services. See https://github.com/hypothesis/h/blob/405680086cd60431c8889580776d03014f2df73a/scripts/run-h-dev-in-docker for a script that you can run locally to do this

I would recommend that you go through the process of doing this locally to get a feel for it and you can then try hosting it in a cloud service.


Client DOcumentation: https://h.readthedocs.io/_/downloads/client/en/latest/pdf/
