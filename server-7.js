var express = require('express');
var app = module.exports = express();
var https = require('https');
var fs = require('fs');
/*var server = https.createServer({
    key: fs.readFileSync('/var/www/blitznet-SME/ssl-cert-snakeoil.key'),
    cert: fs.readFileSync('/var/www/blitznet-SME/ssl-cert-snakeoil.pem'),
    requestCert: true,
    rejectUnauthorized: false
},app);*/

var server = https.createServer({
    key: fs.readFileSync('/var/www/blitznet-SME/ssl-cert-snakeoil.key'),
    cert: fs.readFileSync('/var/www/blitznet-SME/ssl-cert-snakeoil.pem'),
    ca: fs.readFileSync('/var/www/blitznet-SME/ca-certificates.crt'),
    requestCert: true,
    rejectUnauthorized: false
},app);

server.listen(3000); //listen on port 3000

var io = require('socket.io')(server, {
    cors: {
        origins: "*:*",
        methods: ["GET", "POST"]
    }
});

var Redis = require('ioredis');
var redis = new Redis();

redis.subscribe('buyer-notification-chanel', function() {
    console.log('buyer Notification to buyer channel');
});
redis.subscribe('buyer-rfq-notification-chanel', function() {
    console.log('rfq Notification channel');
});
redis.subscribe('buyer-order-notification-channel', function() {
    console.log('Order Notification channel');
});
redis.subscribe('send-message-chanel', function() {
    console.log('Send Message channel');
});
redis.subscribe('rfqs-notification-chanel', function() {
    console.log('rfqs Notification channel');
});

redis.subscribe('rfqs-notification-chanel', function() {
    console.log('rfqs Notification channel');
});
redis.subscribe('rfqs-count-chanel', function() {
    console.log('rfqs count Notification channel');
});
redis.subscribe('quotes-count-chanel', function() {
    console.log('quotes count Notification channel');
});
redis.subscribe('orders-count-chanel', function() {
    console.log('orders count Notification channel');
});
redis.subscribe('order-delivery-seprate', function() {
    console.log('orders delivery seprate channel');
});

redis.on('message', function(channel, message) {

    if (channel == 'buyer-notification-chanel'){
        io.emit(channel +':App\\Events\\BuyerNotificationEvent');
    }
    if (channel == 'buyer-rfq-notification-chanel'){
        io.emit(channel +':App\\Events\\BuyerRfqNotificationEvent');
    }
    if (channel == 'buyer-order-notification-channel'){
        io.emit(channel +':App\\Events\\BuyerOrderNotificationEvent');
    }
    if (channel == 'send-message-chanel'){
        io.emit(channel +':App\\Events\\MessageSentEvent', JSON.parse(message).data.data);
    }
    if (channel == 'rfqs-notification-chanel'){
        io.emit(channel +':App\\Events\\rfqsEvent', JSON.parse(message).data.data);
    }
    if (channel == 'rfqs-count-chanel'){
        io.emit(channel +':App\\Events\\rfqsCountEvent', JSON.parse(message).data.data);
    }
    if (channel == 'quotes-count-chanel'){
        io.emit(channel +':App\\Events\\quotesCountEvent', JSON.parse(message).data.data);
    }
    if (channel == 'orders-count-chanel'){
        io.emit(channel +':App\\Events\\ordersCountEvent', JSON.parse(message).data.data);
    }
    if (channel == 'order-delivery-seprate'){
        io.emit(channel +':App\\Events\\OrderDeliverySeprate', JSON.parse(message).data.data);
    }

});

io.on('connection', (socket) => {
    console.log('Connection');

    socket.on('disconnect', (socket) => {
        console.log('Disconnect');
    });
});




