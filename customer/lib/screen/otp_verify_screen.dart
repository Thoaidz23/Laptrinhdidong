import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
import '../services/api_service.dart';
import 'reset_password_screen.dart';

class OtpVerifyScreen extends StatefulWidget {
  final String email;

  const OtpVerifyScreen({super.key, required this.email});

  @override
  State<OtpVerifyScreen> createState() => _OtpVerifyScreenState();
}

class _OtpVerifyScreenState extends State<OtpVerifyScreen> {
  final _otpController = TextEditingController();
  bool _isVerifying = false;

  Future<void> _verifyOtp() async {
    final otp = _otpController.text.trim();
    if (otp.isEmpty) {
      _showMessage("Vui lòng nhập mã OTP");
      return;
    }

    setState(() => _isVerifying = true);

    final response = await http.post(
      Uri.parse("${ApiService.baseUrl}/verify_otp.php"),
      headers: {"Content-Type": "application/json"},
      body: jsonEncode({
        "email": widget.email,
        "otp": otp,
        "purpose": "forgot_password",
      }),
    );

    final data = jsonDecode(response.body);
    setState(() => _isVerifying = false);

    if (data['status'] == true) {
      Navigator.push(
        context,
        MaterialPageRoute(
          builder: (_) => ResetPasswordScreen(email: widget.email),
        ),
      );
    } else {
      _showMessage(data['message']);
    }
  }

  void _showMessage(String msg) {
    ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text(msg)));
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text("Xác minh OTP")),
      body: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          children: [
            Text("Nhập mã OTP gửi tới ${widget.email}"),
            const SizedBox(height: 20),
            TextField(
              controller: _otpController,
              decoration: const InputDecoration(labelText: "Mã OTP"),
              keyboardType: TextInputType.number,
            ),
            const SizedBox(height: 20),
            ElevatedButton(
              onPressed: _isVerifying ? null : _verifyOtp,
              child: _isVerifying
                  ? const CircularProgressIndicator()
                  : const Text("Xác minh"),
            ),
          ],
        ),
      ),
    );
  }
}
