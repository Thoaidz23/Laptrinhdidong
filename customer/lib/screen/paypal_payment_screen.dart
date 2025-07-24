  import 'package:flutter/material.dart';
  import 'package:webview_flutter/webview_flutter.dart';

  class PaypalPaymentScreen extends StatelessWidget {
    final double amountUSD;
    final VoidCallback onSuccess;

    const PaypalPaymentScreen({super.key, required this.amountUSD, required this.onSuccess});

    @override
    Widget build(BuildContext context) {
      final controller = WebViewController()
        ..setJavaScriptMode(JavaScriptMode.unrestricted)
        ..setNavigationDelegate(NavigationDelegate(
          onNavigationRequest: (request) {
            if (request.url.contains('payment-success')) {
              onSuccess();
              Navigator.pop(context);
              return NavigationDecision.prevent;
            }
            return NavigationDecision.navigate;
          },
        ))
        ..loadRequest(Uri.parse(
            'http://10.0.2.2/ttsfood/api/create-payment.php?amount=${amountUSD.toStringAsFixed(2)}'));
      return Scaffold(
        appBar: AppBar(title: const Text('Thanh toán PayPal')),
        body: WebViewWidget(controller: controller),
      );
    }
  }
