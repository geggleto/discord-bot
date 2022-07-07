# Discord Bot with Backend

This bot utilizes both Node and PHP to do what they're good at. Node is great with sockets, and has an excellent
discord.js SDK. 

PHP is great a putting stuff in a database and executing logic.

We bridge the 2 runtimes with a message broker (RabbitMq). 

# But Why?

Ever used a bot that ends up crashing under load? Yeah that's why. The Bot is a simple JS program that shoves stuff into a queue.
It's not actually doing any actual work aside from holding a reference to discord message. The chances of nerds slamming the bot to the
poiint of it running out of memory from that is way less than killing a bot with background tasks.

The queue acts as a buffer of sorts. You can spin up/down as many consumers as you want/need to handle load.

# Usage
At your own risk. Dont commit the .env to source control.
Copy the .env.example to .env and add your bot token, enjoy building.

# But i dont like php
Too bad.

