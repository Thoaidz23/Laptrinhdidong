import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
import '../services/api_service.dart';
import 'login_screen.dart';

class ResetPasswordScreen extends StatefulWidget {
  final String email;

  const ResetPasswordScreen({super.key, required this.email});

  @override
  State<ResetPasswordScreen> createState() => _ResetPasswordScreenState();
}

class _ResetPasswordScreenState extends State<ResetPasswordScreen> {
  final _passController = TextEditingController();
  final _confirmController = TextEditingController();
  bool _isResetting = false;

  Future<void> _resetPassword() async {
    final pass = _passController.text.trim();
    final confirm = _confirmController.text.trim();

    if (pass.isEmpty || confirm.isEmpty) {
      _showMessage("Vui lòng nhập đầy đủ mật khẩu");
      return;
    }

    if (pass != confirm) {
      _showMessage("Mật khẩu không khớp");
      return;
    }

    setState(() => _isResetting = true);

    final response = await http.post(
      Uri.parse("${ApiService.baseUrl}/reset_password.php"),
      headers: {"Content-Type": "application/json"},
      body: jsonEncode({
        "email": widget.email,
        "new_password": pass,
      }),
    );

    final data = jsonDecode(response.body);
    setState(() => _isResetting = false);

    if (data['status'] == true) {
      _showMessage("Đổi mật khẩu thành công");
      Navigator.pushAndRemoveUntil(
        context,
        MaterialPageRoute(builder: (_) => const LoginScreen()),
            (Route<dynamic> route) => false, // Xóa hết các màn hình trước đó
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
      appBar: AppBar(title: const Text("Đặt lại mật khẩu")),
      body: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          children: [
            TextField(
              controller: _passController,
              decoration: const InputDecoration(labelText: "Mật khẩu mới"),
              obscureText: true,
            ),
            const SizedBox(height: 16),
            TextField(
              controller: _confirmController,
              decoration: const InputDecoration(labelText: "Xác nhận mật khẩu"),
              obscureText: true,
            ),
            const SizedBox(height: 20),
            ElevatedButton(
              onPressed: _isResetting ? null : _resetPassword,
              child: _isResetting
                  ? const CircularProgressIndicator()
                  : const Text("Cập nhật mật khẩu"),
            ),
          ],
        ),
      ),
    );
  }
}
