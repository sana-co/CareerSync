<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= ROOT ?>assets/css/common.css">
    <link rel="stylesheet" href="<?= ROOT ?>assets/css/dashboard/paymentGateway.css">
    <title>Payment</title>
</head>

<body>

    <?php include("components/navbar.php"); ?>

    <div class='page-content'>
        <h2>Company Subscription</h2>
        <p>Amount: LKR <?= $amount ?></p>
        <p><?= htmlspecialchars($description) ?></p>

        <button id="payhere-payment">Pay Now</button>
    </div>

    <script src="https://www.payhere.lk/lib/payhere.js"></script>

    <script>
        const orderId = "ORDER_" + Math.floor(Math.random() * 100000) + "_USER_<?= $_SESSION['USER']->user_id ?>";


        payhere.onCompleted = function(orderId) {
            window.location.href = "<?= ROOT ?>?url=paymentGateway/success";
        };

        payhere.onDismissed = function() {
            alert("Payment cancelled");
        };

        payhere.onError = function(error) {
            alert("Payment error: " + error);
        };

        document.getElementById("payhere-payment").onclick = function() {

            fetch("<?= ROOT ?>?url=paymentGateway/hash", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded"
                    },
                    body: new URLSearchParams({
                        order_id: orderId,
                        amount: <?= $amount ?>
                    })
                })
                .then(res => res.json())
                .then(data => {

                    const payment = {
                        sandbox: true,
                        merchant_id: "1233775",
                        return_url: "<?= ROOT ?>?url=paymentGateway/success",
                        cancel_url: "<?= ROOT ?>?url=paymentGateway/cancel",
                        notify_url: "<?= ROOT ?>?url=paymentGateway/notify",

                        order_id: orderId,
                        items: "Company Subscription",
                        amount: "5000.00",
                        currency: "LKR",
                        hash: data.hash,

                        first_name: "Test",
                        last_name: "User",
                        email: "test@example.com",
                        phone: "0771234567",
                        address: "Sri Lanka",
                        city: "Colombo",
                        country: "Sri Lanka",
                    };


                    console.log("Payment object:", payment);

                    payhere.startPayment(payment);
                })
                .catch(err => {
                    alert("Error generating payment hash: " + err);
                    console.error(err);
                });
        };
    </script>

</body>

</html>