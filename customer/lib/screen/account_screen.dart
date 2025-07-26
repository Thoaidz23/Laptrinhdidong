import 'package:flutter/material.dart';
import '../Widget/Header.dart';
import '../model/user.dart';
import '../screen/changePassword.dart';
import '../services/api_service.dart';
import 'dart:convert';
import 'package:http/http.dart' as http;

class AccountPage extends StatefulWidget {
  const AccountPage({super.key});

  @override
  State<AccountPage> createState() => _AccountPageState();
}

class _AccountPageState extends State<AccountPage> {
  bool isEditingName = false;
  bool isEditingPhone = false;
  bool isEditingEmail = false;
  bool isEditingAddress = false;


  final nameController = TextEditingController();
  final phoneController = TextEditingController();
  final emailController = TextEditingController();
  final addressController = TextEditingController();

  bool isLoading = true;

  @override
  void initState() {
    super.initState();
    _loadUserData();
  }

  Future<void> _loadUserData() async {
    if (currentUser == null) {
      WidgetsBinding.instance.addPostFrameCallback((_) {
        Navigator.pushReplacementNamed(context, '/login');
      });
      return;
    }

    final user = await ApiService().fetchUserById(currentUser!.id);
    if (user != null) {
      currentUser = user;
      setState(() {
        nameController.text = user.name;
        phoneController.text = user.phone;
        emailController.text = user.email;
        addressController.text = user.address;
        isLoading = false;
      });
    } else {
      setState(() => isLoading = false);
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text("Không thể tải thông tin người dùng")),
      );
    }
  }

  Future<void> _saveChanges() async {
    if (currentUser == null) return;

    final updatedUser = User(
      id: currentUser!.id,
      name: nameController.text,
      phone: phoneController.text,
      email: emailController.text,
      address: addressController.text,
      lock_account: currentUser!.lock_account,
    );

    final success = await ApiService.updateUser(updatedUser);
    if (!mounted) return;

    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(
        content: Text(success ? 'Cập nhật thành công' : 'Cập nhật thất bại'),
      ),
    );
  }

  void _changePassword() {
    Navigator.push(
      context,
      MaterialPageRoute(builder: (context) => const ChangePasswordPage()),
    );
  }

  void _lockAccount() {
    showDialog(
      context: context,
      builder: (context) => AlertDialog(
        title: const Text('Xác nhận khóa tài khoản'),
        content: const Text('Bạn có chắc chắn muốn khóa tài khoản? Bạn sẽ không thể đăng nhập nếu không đặt lại mật khẩu.'),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context),
            child: const Text('Không'),
          ),
          ElevatedButton(
            onPressed: () async {
              Navigator.pop(context); // Đóng dialog
              final response = await http.post(
                Uri.parse("${ApiService.baseUrl}/lock_account.php"),
                headers: {"Content-Type": "application/json"},
                body: jsonEncode({"id_user": currentUser!.id}),

              );
              final data = jsonDecode(response.body);

              if (data['status'] == true) {
                ScaffoldMessenger.of(context).showSnackBar(
                  const SnackBar(content: Text("Tài khoản đã bị khóa. Vui lòng đăng nhập lại để kiểm tra.")),
                );
                setState(() => currentUser = null);
                Navigator.pushNamedAndRemoveUntil(context, '/', (route) => false);
              } else {
                ScaffoldMessenger.of(context).showSnackBar(
                  SnackBar(content: Text(data['message'] ?? "Lỗi khi khóa tài khoản")),
                );
              }
            },
            child: const Text('Đồng ý'),
          ),
        ],
      ),
    );
    print("🔒 Gửi yêu cầu khóa tài khoản với ID: ${currentUser!.id}");
  }



  void _confirmLogout() {
    showDialog(
        context: context,
        builder: (context) => Center(
          child: AlertDialog(
              title: const Text('Xác nhận đăng xuất'),
              content: const Text('Bạn có chắc chắn muốn đăng xuất?'),
              actionsAlignment: MainAxisAlignment.spaceBetween,
              actions: [
              TextButton(
              onPressed: () {
        Navigator.of(context).pop();
        setState(() => currentUser = null);
        Navigator.pushNamedAndRemoveUntil(context, '/', (route) => false);
        },child: const Text('Có'),
              ),
                TextButton(onPressed: () => Navigator.of(context).pop(),
                  child: const Text('Không'),
                ),
              ],
          ),
        ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
        backgroundColor: const Color(0xFFF5F5F5),
        body: Column(
            children: [
            const Header(),

        // Tiêu đề
        Container(
          width: double.infinity,
          padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
          decoration: const BoxDecoration(
            color: Colors.white,
            boxShadow: [
              BoxShadow(color: Colors.black12, blurRadius: 4, offset: Offset(0, 2)),
            ],
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
                    'Thông tin tài khoản',
                    style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold),
                  ),
                ),
              ),
              const SizedBox(width: 48),
            ],
          ),
        ),

        // Nội dung chính
        Expanded(
            child: isLoading
                ? const Center(child: CircularProgressIndicator())
                : SingleChildScrollView(
                child: Padding(
                    padding: const EdgeInsets.all(16),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        const Text(
                          'Tài khoản',
                          style: TextStyle(
                            fontSize: 18,
                            fontWeight: FontWeight.bold,
                            color: Colors.black,
                          ),
                        ),
                        const SizedBox(height: 12),

                        // Form thông tin người dùng
                        // Form thông tin người dùng
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
                              // Họ và tên
                              TextField(
                                controller: nameController,
                                readOnly: !isEditingName,
                                decoration: InputDecoration(
                                  labelText: 'Họ và tên',
                                  border: const OutlineInputBorder(),
                                  suffixIcon: IconButton(
                                    icon: Icon(isEditingName ? Icons.check : Icons.edit),
                                    onPressed: () {
                                      setState(() {
                                        isEditingName = !isEditingName;
                                      });
                                    },
                                  ),
                                ),
                              ),

                              const SizedBox(height: 16),

                              // Số điện thoại
                              TextField(
                                controller: phoneController,
                                readOnly: !isEditingPhone,
                                decoration: InputDecoration(
                                  labelText: 'Số điện thoại',
                                  border: const OutlineInputBorder(),
                                  suffixIcon: IconButton(
                                    icon: Icon(isEditingPhone ? Icons.check : Icons.edit),
                                    onPressed: () {
                                      setState(() {
                                        isEditingPhone = !isEditingPhone;
                                      });
                                    },
                                  ),
                                ),
                              ),

                              const SizedBox(height: 16),

                              // Email
                              TextField(
                                controller: emailController,
                                readOnly: true,
                                decoration: const InputDecoration(
                                  labelText: 'Email',
                                  border: OutlineInputBorder(),
                                ),
                              ),


                              const SizedBox(height: 16),

                              // Địa chỉ
                              TextField(
                                controller: addressController,
                                readOnly: !isEditingAddress,
                                minLines: 1,
                                maxLines: 3,
                                decoration: InputDecoration(
                                  labelText: 'Địa chỉ',
                                  border: const OutlineInputBorder(),
                                  suffixIcon: IconButton(
                                    icon: Icon(isEditingAddress ? Icons.check : Icons.edit),
                                    onPressed: () {
                                      setState(() {
                                        isEditingAddress = !isEditingAddress;
                                      });
                                    },
                                  ),
                                ),
                              ),

                              const SizedBox(height: 24),

                              Center(
                                child: ElevatedButton(
                                  onPressed: _saveChanges,
                                  style: ElevatedButton.styleFrom(
                                    backgroundColor: Colors.blue,
                                  ),
                                  child: const Text('Lưu thay đổi'),
                                ),
                              ),
                            ],
                          ),
                        ),

                        const SizedBox(height: 24),

                    const Text(
                      'Bảo mật',
                      style: TextStyle(
                        fontSize: 18,
                        fontWeight: FontWeight.bold,
                        color: Colors.black,
                      ),
                    ),
                    const SizedBox(height: 12),

                    // Các tùy chọn bảo mật
                    Container(
                        padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
                        decoration: BoxDecoration(
                          color: Colors.white,
                          borderRadius: BorderRadius.circular(12),
                          boxShadow: const [
                            BoxShadow(
                              color: Colors.black12,
                              blurRadius: 4,
                              offset: Offset(0, 2),
                            ),
                          ],
                        ),
                        child: Column(
                            children: [
                            InkWell(
                            onTap: _changePassword,child: Container(
                            padding: const EdgeInsets.symmetric(vertical: 12),
                            decoration: const BoxDecoration(
                              border: Border(bottom: BorderSide(color: Colors.black12)),
                            ),
                            child: Row(
                                children: const [
                                Icon(Icons.lock_reset, size: 28),
                                SizedBox(width: 12),
                                Expanded(
                                    child: Text(
                                      'Đổi mật khẩu',style: TextStyle(fontSize: 16),
                                    ),
                                ),
                                  Icon(Icons.chevron_right),
                                ],
                            ),
                            ),
                            ),
                              InkWell(
                                onTap: _lockAccount,
                                child: Container(
                                  padding: const EdgeInsets.symmetric(vertical: 12),
                                  decoration: const BoxDecoration(
                                    border: Border(bottom: BorderSide(color: Colors.black12)),
                                  ),
                                  child: Row(
                                    children: const [
                                      Icon(Icons.lock_outline, size: 28),
                                      SizedBox(width: 12),
                                      Expanded(
                                        child: Text(
                                          'Khoá tài khoản',
                                          style: TextStyle(fontSize: 16),
                                        ),
                                      ),

                                      Icon(Icons.chevron_right),
                                    ],
                                  ),
                                ),
                              ),
                              InkWell(
                                onTap: _confirmLogout,
                                child: Container(
                                  padding: const EdgeInsets.symmetric(vertical: 12),
                                  child: Row(
                                    children: const [
                                      Icon(Icons.logout, size: 28, color: Colors.red),
                                      SizedBox(width: 12),
                                      Expanded(
                                        child: Text(
                                          'Đăng xuất',
                                          style: TextStyle(fontSize: 16, color: Colors.red),),
                                      ),
                                      Icon(Icons.chevron_right, color: Colors.red),
                                    ],
                                  ),
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
            ],
        ),
    );
  }
}