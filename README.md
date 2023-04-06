<p>Service for payment systems applied the design pattern Strategy for more flexibility and adding 
a new payment system without editing the current code. It is used in  online store</p>
<p>The main service code is  in  CheckoutController. The service  is located in the src / service / payment directory. </p>
<p>The CheckoutController accepts which payment system will be paid by the user, and the service  accepts and processes the data and returns whether the payment was successful or not.</p>