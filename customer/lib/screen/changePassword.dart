import 'package:flutter/material.dart';
import '../Widget/Header.dart';
import '../model/user.dart';
import '../services/api_service.dart';

class ChangePasswordPage extends StatefulWidget {
  const ChangePasswordPage({super.key});

  @override
  State<ChangePasswordPage> createState() => _ChangePasswordPageState();
}

class _ChangePasswordPageState extends State<ChangePasswordPage> {
  final _formKey = GlobalKey<FormState>();

  final currentPasswordController = TextEditingController();
  final newPasswordController = TextEditingController();
  final confirmPasswordController = TextEditingController();

  // 👁 Trạng thái hiện/ẩn mật khẩu
  bool _obscureCurrent = true;
  bool _obscureNew = true;
  bool _obscureConfirm = true;

  @override
  void dispose() {
    currentPasswordController.dispose();
    newPasswordController.dispose();
    confirmPasswordController.dispose();
    super.dispose();
  }

  void _handleChangePassword() async {
    if (!_formKey.currentState!.validate()) return;

    final currentPassword = currentPasswordController.text.trim();
    final newPassword = newPasswordController.text.trim();
    final confirmPassword = confirmPasswordController.text.trim();

    if (newPassword != confirmPassword) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text("Mật khẩu mới không khớp.")),
      );
      return;
    }

    if (currentUser == null) return;

    final response = await ApiService.changePassword(
      userId: currentUser!.id,
      currentPassword: currentPassword,
      newPassword: newPassword,
    );

    if (!mounted) return;

    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(content: Text(response['message'])),
    );

    if (response['status'] == true) {
      Navigator.pop(context);
    }
  }

  String? _validatePassword(String? value) {
    if (value == null || value.trim().isEmpty) {
      return "Vui lòng nhập mật khẩu.";
    }
    String pass = value.trim();
    if (pass.length < 8 || pass.length > 30) {
      return "Mật khẩu phải từ 8 đến 30 ký tự.";
    }
    bool hasLetter = pass.contains(RegExp(r'[A-Za-z]'));
    bool hasDigit = pass.contains(RegExp(r'[0-9]'));
    bool hasSpecial = pass.contains(RegExp(r'[!@#\$%^&*(),.?":{}|<>]'));
    if (!hasLetter || !hasDigit || !hasSpecial) {
      return "Mật khẩu phải có chữ, số và ký tự đặc biệt.";
    }
    return null;
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
        backgroundColor: const Color(0xFFF5F5F5),
        body: Column(
            children: [
            const Header(),

        // Thanh tiêu đề
        Container(
          width: double.infinity,
          padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
          decoration: const BoxDecoration(
              color: Colors.white,
              boxShadow: [
              BoxShadow(color: Colors.black12, blurRadius: 4, offset: Offset(0, 2)),],
          ),
          child: Row(
            children: [
              IconButton(
                icon: const Icon(Icons.arrow_back),
                onPressed: () => Navigator.pop(context),
              ),
              const Expanded(
                child: Center(
                  child: Text(
                    'Đổi mật khẩu',
                    style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold),
                  ),
                ),
              ),
              const SizedBox(width: 48),
            ],
          ),
        ),

        // Nội dung form
        Expanded(
            child: SingleChildScrollView(
                child: Padding(
                    padding: const EdgeInsets.all(16),
                    child: Form(
                        key: _formKey,
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                          const Text(
                          'Thông tin bảo mật',
                          style: TextStyle(
                            fontSize: 18,
                            fontWeight: FontWeight.bold,
                            color: Colors.black,
                          ),
                        ),
                        const SizedBox(height: 12),
                        Container(
                            padding: const EdgeInsets.all(16),
                            decoration: BoxDecoration(
                              color: Colors.white,
                              borderRadius: BorderRadius.circular(12),
                              boxShadow: const [
                                BoxShadow(
                                  color: Colors.black12,
                                  blurRadius: 6,
                                  offset: Offset(0, 2),
                                ),
                              ],
                            ),
                            child: Column(
                                children: [
                                // Mật khẩu hiện tại
                                TextFormField(
                                controller: currentPasswordController,
                                obscureText: _obscureCurrent,
                                validator: _validatePassword,
                                decoration: InputDecoration(
                                    labelText: 'Mật khẩu hiện tại',
                                    border: const OutlineInputBorder(),
                                    suffixIcon: IconButton(
                                      icon: Icon(_obscureCurrent
                                          ? Icons.visibility_off
                                          : Icons.visibility),
                                      onPressed: () {
                                        setState(() {
                                          _obscureCurrent = !_obscureCurrent;
                                        });
                                      },),
                                ),
                            ),
                            const SizedBox(height: 16),

                            // Mật khẩu mới
                            TextFormField(
                              controller: newPasswordController,
                              obscureText: _obscureNew,
                              validator: _validatePassword,
                              decoration: InputDecoration(
                                labelText: 'Mật khẩu mới',
                                border: const OutlineInputBorder(),
                                suffixIcon: IconButton(
                                  icon: Icon(_obscureNew
                                      ? Icons.visibility_off
                                      : Icons.visibility),
                                  onPressed: () {
                                    setState(() {
                                      _obscureNew = !_obscureNew;
                                    });
                                  },
                                ),
                              ),
                            ),
                            const SizedBox(height: 16),

                            // Xác nhận mật khẩu
                            TextFormField(
                              controller: confirmPasswordController,
                              obscureText: _obscureConfirm,
                              validator: (value) {
                                if (value == null || value.trim().isEmpty) {
                                  return "Vui lòng xác nhận mật khẩu.";
                                }
                                if (value.trim() != newPasswordController.text.trim()) {
                                  return "Mật khẩu xác nhận không khớp.";
                                }
                                return null;
                              },
                              decoration: InputDecoration(
                                labelText: 'Xác nhận mật khẩu mới',
                                border: const OutlineInputBorder(),
                                suffixIcon: IconButton(
                                  icon: Icon(_obscureConfirm
                                      ? Icons.visibility_off
                                      : Icons.visibility),
                                  onPressed: () {
                                    setState(() {
                                      _obscureConfirm = !_obscureConfirm;
                                    });
                                  },
                                ),
                              ),
                            ),
                            const SizedBox(height: 24),
                            Center(
                                child: ElevatedButton(
                                  onPressed: _handleChangePassword,style: ElevatedButton.styleFrom(
                                  backgroundColor: Colors.blue,
                                ),
                                  child: const Text('Xác nhận'),
                                ),
                            ),
                                ],
                            ),
                        ),
                          ],
                        ),
                    ),
                ),
            ),
        ),
            ],
        ),
    );
  }
}