const { Client, Intents } = require('discord.js');
const client = new Client({ intents: [Intents.FLAGS.GUILDS, Intents.FLAGS.GUILD_MESSAGES] });
const amqplib = require('amqplib');
const RabbitHelpers = require('./lib/Rabbit/RabbitHelpers.js');
require('dotenv').config();

const amqpUrl = process.env.AMQP_URL || 'amqp://localhost:5673';

client.on('ready', async () => {
    console.log(`Logged in as ${client.user.tag}!`);

    //RabbitConsumer MQ
    const channel = await RabbitHelpers.getChannel(amqpUrl);

    client.on('messageCreate',  (message) => {
        //bot commands
        let command = message.content.split(' ');

        if (command[0] === '!help') {
            message.reply("Help Menu").catch((e) => console.error(e));
        }
        if (command[0] === '!create') {
            RabbitHelpers.createCommand(process.env.RABBIT_QUEUE_NAME,  RabbitHelpers.makeCommand('create', {
                discord_id: message.author.id
            }), (content) => {
                message.reply(content);
            });
        }
    });
});

client.login(process.env.DISCORD_BOT_KEY);