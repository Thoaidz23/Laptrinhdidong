import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import '../Widget/Header.dart';
import '../model/cart_item.dart';
import '../model/user.dart';
import '../services/api_service.dart';
import 'paypal_payment_screen.dart';
import 'main_screen.dart';

import 'package:intl/intl.dart';
final currencyFormatter = NumberFormat("#,###", "vi_VN");

class PaymentScreen extends StatefulWidget {
  final List<CartItem> cartItems;

  const PaymentScreen({super.key, required this.cartItems});

  @override
  State<PaymentScreen> createState() => _PaymentScreenState();
}

class _PaymentScreenState extends State<PaymentScreen> {
  String? _selectedMethod;

  late TextEditingController _nameController;
  late TextEditingController _phoneController;
  late TextEditingController _addressController;

  final FocusNode _nameFocusNode = FocusNode();
  final FocusNode _phoneFocusNode = FocusNode();
  final FocusNode _addressFocusNode = FocusNode();

  bool isEditingName = false;
  bool isEditingPhone = false;
  bool isEditingAddress = false;

  @override
  void initState() {
    super.initState();
    final user = currentUser!;
    _nameController = TextEditingController(text: user.name);
    _phoneController = TextEditingController(text: user.phone);
    _addressController = TextEditingController(text: user.address);
  }

  @override
  void dispose() {
    _nameController.dispose();
    _phoneController.dispose();
    _addressController.dispose();
    _nameFocusNode.dispose();
    _phoneFocusNode.dispose();
    _addressFocusNode.dispose();
    super.dispose();
  }

  double _getTotalVND() {
    return widget.cartItems.fold(0.0, (sum, item) => sum + item.price * item.quantity);
  }

  Future<double?> _convertVNDToUSD(double vndAmount) async {
    const apiKey = "9d18cee183b9ecf318d5eb21"; // Replace with your real API key
    final formattedAmount = vndAmount.toStringAsFixed(2);
    final url = Uri.parse('https://v6.exchangerate-api.com/v6/$apiKey/pair/VND/USD/$formattedAmount');

    try {
      final response = await http.get(url);
      if (response.statusCode != 200) return null;

      final data = json.decode(response.body);
      return (data['conversion_result'] as num).toDouble();
    } catch (e) {
      return null;
    }
  }

  Future<void> _orderNow() async {
    if (_selectedMethod == null) {
      _showSnack("Vui lòng chọn phương thức thanh toán");
      return;
    }

    if (currentUser == null) {
      _showSnack("Không tìm thấy thông tin người dùng");
      return;
    }

    if (_selectedMethod == "bank") {
      _showBankTransferDialog();
    } else if (_selectedMethod == "paypal") {
      await _handlePaypalPayment();
    } else if (_selectedMethod == "momo") {
      // TODO: Handle MoMo integration
    } else {
      await _createOrderOnServer();
    }
  }

  Future<void> _handlePaypalPayment() async {
    final vndTotal = _getTotalVND();
    final usdAmount = await _convertVNDToUSD(vndTotal);

    if (usdAmount == null) {
      _showSnack("Lỗi chuyển đổi tiền tệ");
      return;
    }

    Navigator.push(
      context,
      MaterialPageRoute(
        builder: (_) => PaypalPaymentScreen(
          amountUSD: usdAmount,
          onSuccess: () => _createOrderOnServer(),
        ),
      ),
    );
  }

  Future<void> _createOrderOnServer() async {
    final url = Uri.parse('${ApiService.baseUrl}/create_order.php');

    try {
      final response = await http.post(
        url,
        headers: {'Content-Type': 'application/json'},
        body: json.encode({
          "id_user": currentUser!.id,
          "name_user": _nameController.text.trim(),
          "address": _addressController.text.trim(),
          "phone": _phoneController.text.trim(),
          "method": _selectedMethod == "bank" ? 1 : 0,
          "cart": widget.cartItems.map((item) => {
            "id_product": item.idProduct,
            "quantity": item.quantity,
            "price": item.price,
          }).toList(),
          "isBuyNow": widget.cartItems.length == 1 && widget.cartItems.first.idCart == 0,
        }),
      );

      final result = json.decode(response.body);
      if (result['status'] == true) {
        _showSnack("Đặt hàng thành công");
        await Future.delayed(const Duration(seconds: 1));
        Navigator.pushAndRemoveUntil(
          context,
          MaterialPageRoute(builder: (_) => const MainScreen(initialIndex: 3)),
              (route) => false,
        );
      } else {
        _showSnack("Lỗi: ${result['message']}");
      }
    } catch (e) {
      _showSnack("Lỗi kết nối: $e");
    }
  }

  void _showBankTransferDialog() {
    showDialog(
      context: context,
      builder: (_) => AlertDialog(
        title: const Text('Thông tin chuyển khoản'),
        content: Column(
          mainAxisSize: MainAxisSize.min,
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            const Text('Ngân hàng: Vietcombank'),
            const Text('Số tài khoản: 0123456789'),
            const Text('Chủ tài khoản: NGUYEN VAN A'),
            const SizedBox(height: 8),
            Text('Nội dung: Thanh toán - ${_nameController.text}'),
          ],
        ),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context),
            child: const Text('Đóng'),
          ),
          ElevatedButton(
            onPressed: () {
              Navigator.pop(context);
              _createOrderOnServer();
            },
            child: const Text('Tôi đã chuyển khoản'),
          ),
        ],
      ),
    );
  }

  void _showSnack(String message) {
    ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text(message)));
  }

  @override
  Widget build(BuildContext context) {
    final user = currentUser;
    final cartItems = widget.cartItems;

    return Scaffold(
      backgroundColor: const Color(0xFFF5F5F5),
      body: Column(
        children: [
          const Header(),
          Container(
            padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 6),
            color: Colors.orange,
            child: Row(
              children: [
                IconButton(
                  icon: const Icon(Icons.arrow_back),
                  onPressed: () => Navigator.pop(context),
                ),
                const Expanded(
                  child: Center(
                    child: Text(
                      'Thanh toán',
                      style: TextStyle(fontSize: 20, color: Colors.white, fontWeight: FontWeight.bold),
                    ),
                  ),
                ),
                const SizedBox(width: 48),
              ],
            ),
          ),
          Expanded(
            child: user == null
                ? const Center(child: Text("Không tìm thấy người dùng"))
                : SingleChildScrollView(
              padding: const EdgeInsets.all(16),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const Text("Thông tin sản phẩm", style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold)),
                  const SizedBox(height: 8),
                  sectionContainer(
                    child: Column(
                      children: cartItems.map((item) {
                        return Padding(
                          padding: const EdgeInsets.only(bottom: 12),
                          child: Row(
                            children: [
                              ClipRRect(
                                borderRadius: BorderRadius.circular(8),
                                child: Image.network(
                                  item.imageUrl,
                                  width: 60,
                                  height: 60,
                                  fit: BoxFit.cover,
                                ),
                              ),
                              const SizedBox(width: 12),
                              Expanded(
                                child: Column(
                                  crossAxisAlignment: CrossAxisAlignment.start,
                                  children: [
                                    Text(item.name, style: const TextStyle(fontWeight: FontWeight.bold)),
                                    Text("SL: ${item.quantity}"),
                                    Text("Giá: ${NumberFormat('#,###', 'vi_VN').format(item.price)} đ"),
                                  ],
                                ),
                              ),
                            ],
                          ),
                        );
                      }).toList(),
                    ),
                  ),
                  const SizedBox(height: 16),
                  const Text("Thông tin khách hàng", style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold)),
                  const SizedBox(height: 8),
                  sectionContainer(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        // Họ tên
                        TextField(
                          controller: _nameController,
                          focusNode: _nameFocusNode,
                          enabled: isEditingName,
                          decoration: InputDecoration(
                            labelText: 'Họ tên',
                            suffixIcon: IconButton(
                              icon: Icon(
                                isEditingName ? Icons.check : Icons.edit,
                                color: Colors.grey,
                              ),
                              onPressed: () {
                                setState(() => isEditingName = !isEditingName);
                                if (isEditingName) {
                                  Future.delayed(const Duration(milliseconds: 100), () {
                                    FocusScope.of(context).requestFocus(_nameFocusNode);
                                  });
                                }
                              },
                            ),
                            border: OutlineInputBorder(borderRadius: BorderRadius.circular(8)),
                            focusedBorder: OutlineInputBorder(
                              borderRadius: BorderRadius.circular(8),
                              borderSide: const BorderSide(color: Colors.orange, width: 2),
                            ),
                          ),
                        ),
                        const SizedBox(height: 12),

                        // SĐT
                        TextField(
                          controller: _phoneController,
                          focusNode: _phoneFocusNode,
                          keyboardType: TextInputType.phone,
                          enabled: isEditingPhone,
                          decoration: InputDecoration(
                            labelText: 'SĐT',
                            suffixIcon: IconButton(
                              icon: Icon(
                                isEditingPhone ? Icons.check : Icons.edit,
                                color: Colors.grey,
                              ),
                              onPressed: () {
                                setState(() => isEditingPhone = !isEditingPhone);
                                if (isEditingPhone) {
                                  Future.delayed(const Duration(milliseconds: 100), () {
                                    FocusScope.of(context).requestFocus(_phoneFocusNode);
                                  });
                                }
                              },
                            ),
                            border: OutlineInputBorder(borderRadius: BorderRadius.circular(8)),
                            focusedBorder: OutlineInputBorder(
                              borderRadius: BorderRadius.circular(8),
                              borderSide: const BorderSide(color: Colors.orange, width: 2),
                            ),
                          ),
                        ),
                        const SizedBox(height: 12),

                        // Địa chỉ
                        TextField(
                          controller: _addressController,
                          focusNode: _addressFocusNode,
                          enabled: isEditingAddress,
                          decoration: InputDecoration(
                            labelText: 'Địa chỉ',
                            suffixIcon: IconButton(
                              icon: Icon(
                                isEditingAddress ? Icons.check : Icons.edit,
                                color: Colors.grey,
                              ),
                              onPressed: () {
                                setState(() => isEditingAddress = !isEditingAddress);
                                if (isEditingAddress) {
                                  Future.delayed(const Duration(milliseconds: 100), () {
                                    FocusScope.of(context).requestFocus(_addressFocusNode);
                                  });
                                }
                              },
                            ),
                            border: OutlineInputBorder(borderRadius: BorderRadius.circular(8)),
                            focusedBorder: OutlineInputBorder(
                              borderRadius: BorderRadius.circular(8),
                              borderSide: const BorderSide(color: Colors.orange, width: 2),
                            ),
                          ),
                        ),
                      ],
                    ),
                  ),
                  const SizedBox(height: 16),
                  const Text("Phương thức thanh toán", style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold)),
                  const SizedBox(height: 8),
                  sectionContainer(
                    child: Column(
                      children: [
                        buildMethodButton("cod", Icons.money, "Thanh toán khi nhận hàng"),
                        const SizedBox(height: 8),
                        buildMethodButton("momo", Icons.phone_android, "Thanh toán MoMo"),
                        const SizedBox(height: 8),
                        buildMethodButton("paypal", Icons.payment, "Thanh toán bằng PayPal"),
                        const SizedBox(height: 8),
                        buildMethodButton("bank", Icons.account_balance, "Chuyển khoản ngân hàng"),
                      ],
                    ),
                  ),
                ],
              ),
            ),
          ),
          Padding(
            padding: const EdgeInsets.all(16),
            child: SizedBox(
              width: double.infinity,
              height: 50,
              child: ElevatedButton(
                onPressed: _orderNow,
                style: ElevatedButton.styleFrom(
                  backgroundColor: Colors.orange,
                  shape: RoundedRectangleBorder(
                    borderRadius: BorderRadius.circular(8),
                  ),
                ),
                child: const Text(
                  'Thanh toán',
                  style: TextStyle(fontSize: 18, color: Colors.white),
                ),
              ),
            ),
          ),
        ],
      ),
    );
  }

  Widget sectionContainer({required Widget child}) {
    return Container(
      padding: const EdgeInsets.all(16),
      width: double.infinity,
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(12),
        boxShadow: const [BoxShadow(color: Colors.black12, blurRadius: 6, offset: Offset(0, 2))],
      ),
      child: child,
    );
  }

  Widget buildMethodButton(String value, IconData icon, String label) {
    final isSelected = _selectedMethod == value;

    return InkWell(
      onTap: () => setState(() => _selectedMethod = value),
      child: Container(
        padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 14),
        decoration: BoxDecoration(
          color: isSelected ? Colors.orange.withOpacity(0.1) : Colors.white,
          border: Border.all(
            color: isSelected ? Colors.orange : Colors.grey.shade300,
            width: 1.2,
          ),
          borderRadius: BorderRadius.circular(8),
        ),
        child: Row(
          children: [
            Icon(icon, color: isSelected ? Colors.orange : Colors.grey),
            const SizedBox(width: 12),
            Expanded(child: Text(label)),
            if (isSelected) const Icon(Icons.check_circle, color: Colors.orange),
          ],
        ),
      ),
    );
  }
}
