import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'package:webview_flutter/webview_flutter.dart';


class PaypalPaymentScreen extends StatefulWidget {
  final double amountUSD;
  final VoidCallback onSuccess;

  const PaypalPaymentScreen({super.key, required this.amountUSD, required this.onSuccess});

  @override
  State<PaypalPaymentScreen> createState() => _PaypalPaymentScreenState();
}

class _PaypalPaymentScreenState extends State<PaypalPaymentScreen> {
  late final WebViewController _controller;

  @override
  void initState() {
    super.initState();

    _controller = WebViewController()
      ..setJavaScriptMode(JavaScriptMode.unrestricted)
      ..setNavigationDelegate(
        NavigationDelegate(
          onNavigationRequest: (request) {
            _handleNavigation(request.url);
            return NavigationDecision.navigate;
          },
        ),
      )
      ..loadRequest(Uri.parse('about:blank'));

    // ✅ Đảm bảo gọi đúng lúc và chỉ 1 lần
    Future.microtask(() => _createPaypalOrder());
  }


  Future<void> _createPaypalOrder() async {
    try {
      final url = Uri.parse('http://10.0.2.2/ttsfood/api/create-payment.php');
      final response = await http.post(
        url,
        headers: {'Content-Type': 'application/json'},
        body: jsonEncode({'amount_usd': widget.amountUSD}),
      );

      print("📦 Raw PayPal response: ${response.body}");

      final data = jsonDecode(response.body);

      final approvalUrl = data['url']; // ✅ sử dụng đúng key trả về từ PHP

      if (approvalUrl != null && approvalUrl.toString().isNotEmpty) {
        final cookieManager = WebViewCookieManager();
        await cookieManager.clearCookies();

        await _controller.loadRequest(Uri.parse(approvalUrl));
        print("🔗 Loading PayPal approval URL: $approvalUrl");
      } else {
        if (!mounted) return;
        Navigator.pop(context);
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text('Không tạo được đơn PayPal')),
        );
      }
    } catch (e) {
      print("❌ Exception: $e");
      if (!mounted) return;
      Navigator.pop(context);
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Lỗi khi tạo đơn PayPal')),
      );
    }
  }


  Future<void> _capturePaypalOrder(String orderId) async {
    final url = Uri.parse('http://10.0.2.2/ttsfood/api/capture-payment.php');
    final response = await http.post(
      url,
      headers: {'Content-Type': 'application/json'},
      body: jsonEncode({'order_id': orderId}),
    );

    final data = jsonDecode(response.body);
    if (data['status'] == true) {
      widget.onSuccess();
    } else {
      print('❌ Capture thất bại: ${data['error']}');
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Thanh toán không thành công')),
      );
    }
  }


  void _handleNavigation(String url) {
    print("🔍 Navigated to: $url");

    if (url.contains('paypal.com/checkoutnow/')) return;

    if (url.contains('success')) {
      final uri = Uri.parse(url);
      final token = uri.queryParameters['token'];
      if (token != null) {
        _capturePaypalOrder(token);
      }
      Navigator.pop(context);
    } else if (url.contains('cancel')) {
      Navigator.pop(context);
    }
  }





  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Thanh toán PayPal')),
      body: WebViewWidget(controller: _controller),
    );
  }
}
