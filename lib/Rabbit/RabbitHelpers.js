const { v4 } = require('uuid');
const amqplib = require("amqplib");
require('dotenv').config();

module.exports = {
    channel : null,
    async getChannel(amqpUrl) {
        const connection = await amqplib.connect(amqpUrl, "heartbeat=60");
        const channel = await connection.createChannel();
        this.channel = channel;

        // await channel.assertExchange(process.env.RABBIT_EXCHANGE_NAME, 'topic', {
        //     durable: true
        // });

        await channel.assertQueue(process.env.RABBIT_QUEUE_NAME, {durable: true});
        // channel.bindQueue(process.env.RABBIT_QUEUE_NAME.queue, process.env.RABBIT_EXCHANGE_NAME, process.env.RABBIT_QUEUE_NAME);

        return channel;
    },

    async createCommand(commandName, obj, cb) {

        let correlationId = v4();

        let q = await this.channel.assertQueue('', {
            autoDelete: true,
        });

        console.log(q);

        this.channel.consume(q.queue, function(msg) {
            if (msg.properties.correlationId === correlationId) {
                console.log(' [.] Got %s', msg.content.toString());
                cb(msg.content.toString());
            }
            // this.channel.deleteQueue(q.queue);
        }, {
            noAck: true
        });

        await this.channel.sendToQueue(commandName, Buffer.from(JSON.stringify(obj)), {
            correlationId: correlationId,
            replyTo: q.queue
        });
    },
    makeCommand(name, payload) {
        return {
            verison: 1,
            name: name,
            payload: payload
        }
    }
};