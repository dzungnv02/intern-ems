FROM rabbitmq:3-management

ADD rabbitmq.config /etc/rabbitmq/
ADD definitions.json /etc/rabbitmq/
RUN chown rabbitmq:rabbitmq /etc/rabbitmq/rabbitmq.config /etc/rabbitmq/definitions.json

EXPOSE 15672
EXPOSE 5672

# Define default command
CMD ["rabbitmq-server"]
