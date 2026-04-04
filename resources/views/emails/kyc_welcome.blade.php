<!DOCTYPE html>
<html>
<head>
    <title>Welcome to Arewa Smart!</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 1px solid #eeeeee;
        }
        .header h1 {
            color: #333333;
        }
        .content {
            padding: 20px 0;
        }
        .content p {
            color: #555555;
            line-height: 1.6;
        }
        .details {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            margin-top: 10px;
        }
        .details p {
            margin: 5px 0;
            font-weight: bold;
        }
        .services {
            background-color: #e8f4f8;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }
        .services h3 {
            color: #2c3e50;
            margin-bottom: 10px;
            text-align: center;
        }
        .services ul {
            color: #555555;
            padding-left: 20px;
        }
        .services li {
            margin: 5px 0;
        }
        .cta-button {
            display: inline-block;
            background-color: #ff7b00;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            text-align: center;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid #eeeeee;
            color: #999999;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Welcome to Arewa Smart! 🎉</h1>
        </div>
        <div class="content">
            <p>Hello {{ $user->first_name }},</p>
            <p>Congratulations! Your account has been successfully verified and activated. You can now access all our services and start making transactions.</p>

            <div class="services">
                <h3>Available Services</h3>
                <ul>
                    <li>💰 Airtime & Data Top-up</li>
                    <li>⚡ Electricity Bill Payment</li>
                    <li>📺 Cable TV Subscriptions</li>
                    <li>🏦 Bank Transfers & Deposits</li>
                    <li>🎁 Gift Cards Trading</li>
                    <li>📱 Wallet Services</li>
                </ul>
            </div>

            <div style="text-align: center;">
                <a href="{{ url('/dashboard') }}" class="cta-button">🚀 Start Your First Transaction</a>
            </div>

            <div class="details">
                <p>Account Status: ✅ Fully Activated</p>
                <p>Profile Completion: 100%</p>
                <p>Ready for Transactions: Yes</p>
            </div>

            <p>We're excited to have you as part of our community! Start exploring our services and enjoy seamless digital transactions.</p>

            <p>Need help getting started? Contact our support team anytime.</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Arewa Smart. All rights reserved.</p>
        </div>
    </div>
</body>
</html>