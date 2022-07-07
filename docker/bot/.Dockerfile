FROM node:16-alpine
WORKDIR /src
COPY ./ ./

RUN apk add --no-cache bash
RUN wget -O /bin/wait-for-it.sh https://raw.githubusercontent.com/vishnubob/wait-for-it/master/wait-for-it.sh
RUN chmod +x /bin/wait-for-it.sh

ENV NODE_ENV=production

RUN npm ci

CMD ["node", "consumer.js"]
