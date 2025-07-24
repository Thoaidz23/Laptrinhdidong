import 'package:flutter/material.dart';
import '../services/api_service.dart';
import '../widget/header.dart';
import '../widget/MenuBar.dart';

class RegisterScreen extends StatefulWidget {
  const RegisterScreen({super.key});

  @override
  State<RegisterScreen> createState() => _RegisterScreenState();
}

class _RegisterScreenState extends State<RegisterScreen> {
  int _selectedIndex = 0;
  final _formKey = GlobalKey<FormState>();

  final _fullNameController = TextEditingController();
  final _phoneController = TextEditingController();
  final _emailController = TextEditingController();
  final _passwordController = TextEditingController();
  final _confirmPasswordController = TextEditingController();
  final _addressController = TextEditingController();

  bool agree = false;
  bool obscurePass = true;
  bool isLoading = false;
  String message = '';

  void _register() async {
    if (!_formKey.currentState!.validate()) return;

    if (!agree) {
      setState(() => message = 'Vui lòng đồng ý với các chính sách.');
      return;
    }

    setState(() {
      isLoading = true;
      message = '';
    });

    final result = await ApiService.register(
      _fullNameController.text.trim(),
      _emailController.text.trim(),
      _passwordController.text.trim(),
      _phoneController.text.trim(),
      _addressController.text.trim(),
    );

    setState(() {
      isLoading = false;
      message = result['message'] ?? 'Đăng ký thất bại!';
    });

    if (result['status'] == true) {
      Future.delayed(const Duration(seconds: 1), () {
        Navigator.pop(context);
      });
    }
  }

  void _onItemTapped(int index) {
    setState(() {
      _selectedIndex = index;
    });
    // TODO: Chuyển trang nếu cần
  }

  Widget _buildTextFormField({
    required String label,
    required TextEditingController controller,
    bool isPassword = false,
    Widget? suffix,
    String? Function(String?)? validator,
    TextInputType? keyboardType,
  }) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(label, style: const TextStyle(fontWeight: FontWeight.bold)),
        TextFormField(
          controller: controller,
          obscureText: isPassword && obscurePass,
          validator: validator,
          keyboardType: keyboardType,
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
            const Header(),
            Expanded(
              child: SingleChildScrollView(
                padding: const EdgeInsets.symmetric(horizontal: 20),
                child: Form(
                  key: _formKey,
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
                      _buildTextFormField(
                        label: "Họ và tên *",
                        controller: _fullNameController,
                        validator: (value) {
                          if (value == null || value.trim().isEmpty) {
                            return "Vui lòng nhập họ và tên.";
                          }
                          if (value.trim().length < 8 || value.trim().length > 30) {
                            return "Họ và tên phải từ 8 đến 30 ký tự.";
                          }
                          return null;
                        },
                      ),
                      _buildTextFormField(
                        label: "Địa chỉ E-mail *",
                        controller: _emailController,
                        keyboardType: TextInputType.emailAddress,
                        validator: (value) {
                          if (value == null || value.trim().isEmpty) {
                            return "Vui lòng nhập email.";
                          }
                          if (value.trim().length > 30) {
                            return "Email không được quá 30 ký tự.";
                          }
                          if (!RegExp(r'^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$')
                              .hasMatch(value.trim())) {
                            return "Email không hợp lệ.";
                          }
                          return null;
                        },
                      ),
                      _buildTextFormField(
                        label: "Số điện thoại *",
                        controller: _phoneController,
                        keyboardType: TextInputType.phone,
                        validator: (value) {
                          if (value == null || value.trim().isEmpty) {
                            return "Vui lòng nhập số điện thoại.";
                          }
                          if (!RegExp(r'^[0-9]{9,11}$').hasMatch(value.trim())) {
                            return "Số điện thoại không hợp lệ.";
                          }
                          return null;
                        },
                      ),
                      _buildTextFormField(
                        label: "Địa chỉ *",
                        controller: _addressController,
                        validator: (value) {
                          if (value == null || value.trim().isEmpty) {
                            return "Vui lòng nhập địa chỉ.";
                          }
                          if (value.trim().length < 10 || value.trim().length > 100) {
                            return "Địa chỉ phải từ 10 đến 100 ký tự.";
                          }
                          return null;
                        },
                      ),
                      _buildTextFormField(
                        label: "Mật khẩu *",
                        controller: _passwordController,
                        isPassword: true,
                        suffix: IconButton(
                          icon: Icon(
                              obscurePass ? Icons.visibility_off : Icons.visibility),
                          onPressed: () => setState(() => obscurePass = !obscurePass),
                        ),
                        validator: (value) {
                          if (value == null || value.trim().isEmpty) {
                            return "Vui lòng nhập mật khẩu.";
                          }
                          String pass = value.trim();
                          if (pass.length < 8 || pass.length > 30) {
                            return "Mật khẩu phải từ 8 đến 30 ký tự.";
                          }
                          bool hasLetter = pass.contains(RegExp(r'[A-Za-z]'));
                          bool hasDigit = pass.contains(RegExp(r'[0-9]'));
                          bool hasSpecial =
                          pass.contains(RegExp(r'[!@#\$%^&*(),.?":{}|<>]'));
                          if (!hasLetter || !hasDigit || !hasSpecial) {
                            return "Mật khẩu phải có chữ, số và ký tự đặc biệt.";
                          }
                          return null;
                        },
                      ),
                      _buildTextFormField(
                        label: "Xác nhận mật khẩu *",
                        controller: _confirmPasswordController,
                        isPassword: true,
                        suffix: IconButton(
                          icon: Icon(obscurePass ? Icons.visibility_off : Icons.visibility),
                          onPressed: () => setState(() => obscurePass = !obscurePass),
                        ),
                        validator: (value) {
                          if (value == null || value.trim().isEmpty) {
                            return "Vui lòng xác nhận mật khẩu.";
                          }
                          if (value.trim() != _passwordController.text.trim()) {
                            return "Mật khẩu xác nhận không khớp.";
                          }
                          return null;
                        },
                      ),

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
                                  const TextSpan(
                                      text: "Tôi đã đọc và đồng ý với các "),
                                  TextSpan(
                                    text: "Chính Sách Hoạt Động",
                                    style: const TextStyle(
                                        fontWeight: FontWeight.bold),
                                  ),
                                  const TextSpan(text: " và "),
                                  TextSpan(
                                    text: "Chính Sách Bảo Mật Thông Tin",
                                    style: const TextStyle(
                                        fontWeight: FontWeight.bold),
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
                              color: message.contains("thành công")
                                  ? Colors.green
                                  : Colors.red,
                            ),
                          ),
                        ),
                      const SizedBox(height: 16),
                      SizedBox(
                        width: double.infinity,
                        child: ElevatedButton(
                          onPressed: isLoading ? null : _register,
                          style: ElevatedButton.styleFrom(
                            backgroundColor: Colors.green,
                            padding: const EdgeInsets.symmetric(vertical: 14),
                            shape: RoundedRectangleBorder(
                              borderRadius: BorderRadius.circular(8),
                            ),
                            textStyle: const TextStyle(fontSize: 16),
                          ),
                          child: isLoading
                              ? const SizedBox(
                            width: 20,
                            height: 20,
                            child: CircularProgressIndicator(
                              color: Colors.white,
                              strokeWidth: 2,
                            ),
                          )
                              : const Text("Đăng ký",
                              style: TextStyle(color: Colors.white)),
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
