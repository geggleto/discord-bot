const RabbitHelpers = require('../lib/Rabbit/RabbitHelpers.js');

require('dotenv').config();
(async () => {
    const amqpUrl = 'amqp://localhost:5672'; //tests run outside container

    const channel = await RabbitHelpers.getChannel(amqpUrl);

    let obj = {};

    await RabbitHelpers.createCommand(process.env.RABBIT_QUEUE_NAME,  obj, (content) => {
        console.log('Hello');
    });
})();