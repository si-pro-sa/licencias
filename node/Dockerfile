FROM node:14-alpine

# Set working directory
WORKDIR /var/www

# Copy package.json and package-lock.json for installing NPM packages
COPY package*.json ./

# Install NPM packages
RUN npm install --force

COPY . .

RUN npm run prod

