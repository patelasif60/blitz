var fs = require('fs');
var https = require('https');

const httpsServer = https.createServer({
    key: fs.readFileSync("/var/www/blitznet-SME/public/uploads/ssl-cert-snakeoil.key"),
    cert: fs.readFileSync("/var/www/blitznet-SME/public/uploads/ssl-cert-snakeoil.pem"),
    requestCert: true,
    ca: [
        fs.readFileSync("/var/www/blitznet-SME/public/uploads/ca-certificates.crt")
    ]
});

const io = new require('socket.io')(httpsServer, { /* options */ });

io.engine.on("connection", (rawSocket) => {
    // if you need the certificate details (it is no longer available once the handshake is completed)
    rawSocket.peerCertificate = rawSocket.request.client.getPeerCertificate();
});

io.on("connection", (socket) => {
    console.log(socket.conn.peerCertificate);
    // ...
});

httpsServer.listen(3000);

httpsServer.listen(3000,()=>{
    console.log('Server is running. Port '+port);
})
