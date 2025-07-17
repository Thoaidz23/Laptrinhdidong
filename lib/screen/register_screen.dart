import 'package:flutter/material.dart';
import '../services/api_service.dart';
import '../widget/header.dart'; // 👉 Thêm dòng này

class RegisterScreen extends StatefulWidget {
  const RegisterScreen({super.key});

  @override
  State<RegisterScreen> createState() => _RegisterScreenState();
}

class _RegisterScreenState extends State<RegisterScreen> {
  final _lastNameController = TextEditingController();
  final _firstNameController = TextEditingController();
  final _phoneController = TextEditingController();
  final _emailController = TextEditingController();
  final _passwordController = TextEditingController();

  String message = '';
  bool agree = false;
  bool obscurePass = true;

  void _register() async {
    if (!agree) {
      setState(() => message = 'Vui lòng đồng ý với các chính sách.');
      return;
    }

    bool success = await ApiService.register(
      _firstNameController.text + ' ' + _lastNameController.text,
      _emailController.text,
      _passwordController.text,
    );
    if (success) {
      setState(() => message = 'Đăng ký thành công!');
      Future.delayed(const Duration(seconds: 1), () {
        Navigator.pop(context);
      });
    } else {
      setState(() => message = 'Đăng ký thất bại!');
    }
  }

  Widget _buildTextField(String label, TextEditingController controller,
      {bool isPassword = false, Widget? suffix}) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(label, style: const TextStyle(fontWeight: FontWeight.bold)),
        TextField(
          controller: controller,
          obscureText: isPassword && obscurePass,
          decoration: InputDecoration(
            suffixIcon: suffix,
            isDense: true,
          ),
        ),
        const SizedBox(height: 12),
      ],
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: SafeArea(
        child: Column(
          children: [
            const Header(), // 👉 Sử dụng header đã tách

            // Form
            Expanded(
              child: SingleChildScrollView(
                padding: const EdgeInsets.symmetric(horizontal: 20),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    const SizedBox(height: 60),
                    const Center(
                      child: Text(
                        "TẠO TÀI KHOẢN",
                        style: TextStyle(
                          fontSize: 24,
                          fontWeight: FontWeight.bold,
                        ),
                      ),
                    ),
                    const SizedBox(height: 20),
                    _buildTextField("Họ của bạn *", _lastNameController),
                    _buildTextField("Tên của bạn *", _firstNameController),
                    _buildTextField("Số điện thoại *", _phoneController),
                    _buildTextField("Địa chỉ E-mail *", _emailController),
                    _buildTextField(
                      "Mật khẩu *",
                      _passwordController,
                      isPassword: true,
                      suffix: IconButton(
                        icon: Icon(
                          obscurePass ? Icons.visibility_off : Icons.visibility,
                        ),
                        onPressed: () => setState(() => obscurePass = !obscurePass),
                      ),
                    ),

                    // Checkbox
                    Row(
                      children: [
                        Checkbox(
                          value: agree,
                          onChanged: (val) => setState(() => agree = val!),
                        ),
                        Expanded(
                          child: RichText(
                            text: TextSpan(
                              style: const TextStyle(color: Colors.black),
                              children: [
                                const TextSpan(text: "Tôi đã đọc và đồng ý với các "),
                                TextSpan(
                                  text: "Chính Sách Hoạt Động",
                                  style: const TextStyle(fontWeight: FontWeight.bold),
                                ),
                                const TextSpan(text: " và "),
                                TextSpan(
                                  text: "Chính Sách Bảo Mật Thông Tin",
                                  style: const TextStyle(fontWeight: FontWeight.bold),
                                ),
                              ],
                            ),
                          ),
                        ),
                      ],
                    ),

                    if (message.isNotEmpty)
                      Padding(
                        padding: const EdgeInsets.only(top: 8),
                        child: Text(
                          message,
                          style: TextStyle(
                            color: message.contains("thành công") ? Colors.green : Colors.red,
                          ),
                        ),
                      ),

                    const SizedBox(height: 16),
                    ElevatedButton(
                      onPressed: _register,
                      style: ElevatedButton.styleFrom(
                        backgroundColor: Colors.green,
                        padding: const EdgeInsets.symmetric(vertical: 14),
                        shape: RoundedRectangleBorder(
                          borderRadius: BorderRadius.circular(8),
                        ),
                        textStyle: const TextStyle(fontSize: 16),
                      ),
                      child: const Center(
                        child: Text(
                          "Đăng ký",
                          style: TextStyle(color: Colors.white),
                        ),
                      ),
                    ),

                    const SizedBox(height: 40),
                    Row(
                      mainAxisAlignment: MainAxisAlignment.center,
                      children: [
                        const Text("Bạn đã có tài khoản? "),
                        GestureDetector(
                          onTap: () => Navigator.pop(context),
                          child: const Text(
                            "Đăng nhập",
                            style: TextStyle(
                              color: Colors.blue,
                              fontWeight: FontWeight.bold,
                              decoration: TextDecoration.underline,
                            ),
                          ),
                        ),
                      ],
                    ),
                    const SizedBox(height: 20),
                  ],
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }
}
