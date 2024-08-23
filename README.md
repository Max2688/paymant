<strong>How to install:</strong>

1. Build all containers  docker-compose up -d --build
2. Enter to the php container shell <code>docker exec -it "containerID" bash</code>. 
   <p>Don't forget to rename .env.example to .env and run:</p>
   <p>cd ..</p>
   <p>cd symfony_payment</p>
   <p>composer install</p>
3. Open http://localhost
4. To make a payment request run <code>api/process/payment/{shift4|aci}</code>
5. To run payment command run <code> php bin\console process:payment {shift4|aci} {amount} {currency}