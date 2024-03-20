FROM node:20-alpine
# zwiekszylem wersje do 20

WORKDIR /react
# dla wlasnego zdrowia psychicznego zmienilem z app na react
# bo mylily mi sie te foldery od reacta i django
COPY package.json ./

ENV PATH /react/node_modules/.bin:$PATH

RUN npm install

COPY . /react

RUN npm run build

CMD ["npm", "start"]
