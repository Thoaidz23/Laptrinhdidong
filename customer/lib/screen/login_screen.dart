import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import '../model/user.dart';
import '../screen/home_screen.dart';
import '../Widget/Header.dart';
import '../Widget/MenuBar.dart';
import 'main_screen.dart';
class LoginScreen extends StatefulWidget {
  const LoginScreen({super.key});

  @override
  State<LoginScreen> createState() => _LoginScreenState();
}

class _LoginScreenState extends State<LoginScreen> {
  final TextEditingController _emailController = TextEditingController();
  final TextEditingController _passwordController = TextEditingController();
  bool _obscurePassword = true;
  String _errorMessage = '';
  int _selectedIndex = 0;

  Future<void> _login() async {
    final email = _emailController.text.trim();
    final password = _passwordController.text.trim();

    if (email.isEmpty || password.isEmpty) {
      setState(() => _errorMessage = 'Vui lòng nhập đầy đủ thông tin');
      return;
    }

    try {
      final response = await http.post(
        Uri.parse('http://10.0.2.2/ttsfood/api/login.php'),
        headers: {'Content-Type': 'application/json'},
        body: jsonEncode({'email': email, 'password': password}),
      );

      final body = utf8.decode(response.bodyBytes);
      if (response.statusCode == 200 && body.startsWith('{')) {
        final data = jsonDecode(body);
        if (data['status'] == true) {
          currentUser = User.fromJson(data['user']);
          Navigator.pop(context); // trở về MainScreen có MenuBar

        } else {
          setState(() => _errorMessage = data['message'] ?? 'Sai thông tin đăng nhập');
        }
      } else {
        setState(() => _errorMessage = 'Lỗi phản hồi từ máy chủ');
      }
    } catch (e) {
      setState(() => _errorMessage = 'Không thể kết nối tới server');
    }
  }

  void _onItemTapped(int index) {
    setState(() => _selectedIndex = index);
    // TODO: Chuyển trang nếu cần
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.white,
      body: SafeArea(
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            const Header(),
            Expanded(
              child: SingleChildScrollView(
                padding: const EdgeInsets.all(20),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    const SizedBox(height: 40),
                    const Center(
                      child: Text(
                        'ĐĂNG NHẬP',
                        style: TextStyle(fontSize: 24, fontWeight: FontWeight.bold),
                      ),
                    ),
                    const SizedBox(height: 30),
                    TextField(
                      controller: _emailController,
                      decoration: const InputDecoration(
                        labelText: 'Địa chỉ E-mail *',
                        border: UnderlineInputBorder(),
                      ),
                    ),
                    const SizedBox(height: 20),
                    TextField(
                      controller: _passwordController,
                      obscureText: _obscurePassword,
                      decoration: InputDecoration(
                        labelText: 'Mật khẩu *',
                        border: const UnderlineInputBorder(),
                        suffixIcon: IconButton(
                          icon: Icon(
                            _obscurePassword ? Icons.visibility : Icons.visibility_off,
                          ),
                          onPressed: () =>
                              setState(() => _obscurePassword = !_obscurePassword),
                        ),
                      ),
                    ),
                    Align(
                      alignment: Alignment.centerRight,
                      child: TextButton(
                        onPressed: () {},
                        child: const Text('Bạn quên mật khẩu?'),
                      ),
                    ),
                    const SizedBox(height: 10),
                    if (_errorMessage.isNotEmpty)
                      Padding(
                        padding: const EdgeInsets.only(bottom: 10),
                        child: Text(_errorMessage, style: const TextStyle(color: Colors.red)),
                      ),
                    SizedBox(
                      width: double.infinity,
                      child: ElevatedButton(
                        onPressed: _login,
                        style: ElevatedButton.styleFrom(
                          backgroundColor: Colors.green,
                          padding: const EdgeInsets.symmetric(vertical: 14),
                          shape: RoundedRectangleBorder(
                            borderRadius: BorderRadius.circular(30),
                          ),
                        ),
                        child: const Text(
                          'Đăng nhập',
                          style: TextStyle(fontSize: 16, color: Colors.white),
                        ),
                      ),
                    ),
                    const SizedBox(height: 30),
                    Center(
                      child: GestureDetector(
                        onTap: () {
                          Navigator.pushNamed(context, '/register');
                        },
                        child: const Text.rich(
                          TextSpan(
                            text: 'Bạn chưa có tài khoản? ',
                            children: [
                              TextSpan(
                                text: 'Đăng ký',
                                style: TextStyle(fontWeight: FontWeight.bold),
                              ),
                            ],
                          ),
                        ),
                      ),
                    ),
                  ],
                ),
              ),
            ),
          ],
        ),
      ),
      bottomNavigationBar: BottomNavBar(
        currentIndex: _selectedIndex,
        onTap: (index) {
          setState(() => _selectedIndex = index);

          if (index == 0 || index == 1 || index == 4) {
            Navigator.pushReplacement(
              context,
              MaterialPageRoute(builder: (_) => MainScreen(initialIndex: index)),
            );
          } else {
            ScaffoldMessenger.of(context).showSnackBar(
              const SnackBar(content: Text('Vui lòng đăng nhập để tiếp tục')),
            );
          }
        },
      ),
    );
  }
}
