import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import '../model/user.dart';
import '../screen/home_screen.dart';
import '../Widget/Header.dart';
import '../Widget/MenuBar.dart';

class LoginScreen extends StatefulWidget {
  const LoginScreen({super.key});

  @override
  State<LoginScreen> createState() => _LoginScreenState();
}

class _LoginScreenState extends State<LoginScreen> {
  final TextEditingController _emailController = TextEditingController();
  final TextEditingController _passwordController = TextEditingController();

  bool _obscurePassword = true;
  String _emailError = '';
  String _passwordError = '';
  String _loginError = '';
  int _selectedIndex = 0;

  bool _validateForm(String email, String password) {
    setState(() {
      _emailError = '';
      _passwordError = '';
      _loginError = '';
    });

    final emailRegex = RegExp(r"^[\w\.-]+@[\w\.-]+\.\w{2,}$");
    bool isValid = true;

    // Email
    if (!emailRegex.hasMatch(email)) {
      _emailError = 'Email không đúng định dạng';
      isValid = false;
    } else if (email.length < 8) {
      _emailError = 'Email phải có ít nhất 8 ký tự';
      isValid = false;
    } else if (email.length > 30) {
      _emailError = 'Email không được vượt quá 30 ký tự';
      isValid = false;
    }

    // Mật khẩu
    if (password.length < 8) {
      _passwordError = 'Mật khẩu phải từ 8 ký tự trở lên';
      isValid = false;
    } else if (password.length > 30) {
      _passwordError = 'Mật khẩu không vượt quá 30 ký tự';
      isValid = false;
    } else {
      if (!RegExp(r'[A-Z]').hasMatch(password)) {
        _passwordError = 'Mật khẩu phải chứa ít nhất 1 chữ hoa';
        isValid = false;
      } else if (!RegExp(r'\d').hasMatch(password)) {
        _passwordError = 'Mật khẩu phải chứa ít nhất 1 số';
        isValid = false;
      } else if (!RegExp(r'[^A-Za-z0-9]').hasMatch(password)) {
        _passwordError = 'Mật khẩu phải chứa ký tự đặc biệt';
        isValid = false;
      }
    }

    setState(() {}); // để hiển thị lỗi

    return isValid;
  }

  Future<void> _login() async {
    final email = _emailController.text.trim();
    final password = _passwordController.text.trim();

    if (!_validateForm(email, password)) return;

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
          Navigator.pop(context); // thành công → trở về
        } else {
          setState(() => _loginError = data['message'] ?? 'Sai thông tin đăng nhập');
        }
      } else {
        setState(() => _loginError = 'Lỗi phản hồi từ máy chủ');
      }
    } catch (e) {
      setState(() => _loginError = 'Không thể kết nối tới server');
    }
  }

  void _onItemTapped(int index) {
    setState(() => _selectedIndex = index);
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

                    // Email input
                    TextField(
                      controller: _emailController,
                      decoration: const InputDecoration(
                        labelText: 'Địa chỉ E-mail *',
                        border: UnderlineInputBorder(),
                      ),
                    ),
                    if (_emailError.isNotEmpty)
                      Padding(
                        padding: const EdgeInsets.only(top: 4),
                        child: Text(_emailError, style: const TextStyle(color: Colors.red)),
                      ),

                    const SizedBox(height: 20),

                    // Password input
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
                    if (_passwordError.isNotEmpty)
                      Padding(
                        padding: const EdgeInsets.only(top: 4),
                        child: Text(_passwordError, style: const TextStyle(color: Colors.red)),
                      ),

                    Align(
                      alignment: Alignment.centerRight,
                      child: TextButton(
                        onPressed: () {},
                        child: const Text('Bạn quên mật khẩu?'),
                      ),
                    ),

                    if (_loginError.isNotEmpty)
                      Padding(
                        padding: const EdgeInsets.only(top: 10),
                        child: Text(_loginError, style: const TextStyle(color: Colors.red)),
                      ),

                    const SizedBox(height: 10),
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
        onTap: _onItemTapped,
      ),
    );
  }
}
