import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import '../Widget/Header.dart';
import '../model/cart_item.dart';
import '../model/user.dart';
import '../services/api_service.dart';
import 'paypal_payment_screen.dart';
import 'order_history_screen.dart';
import 'main_screen.dart';

class PaymentScreen extends StatefulWidget {
  final List<CartItem> cartItems;

  const PaymentScreen({super.key, required this.cartItems});

  @override
  State<PaymentScreen> createState() => _PaymentScreenState();
}

class _PaymentScreenState extends State<PaymentScreen> {
  String? _selectedMethod;

  double _getTotalVND() {
    return widget.cartItems.fold(
      0.0,
          (sum, item) => sum + item.price * item.quantity,
    );
  }

  Future<double?> _convertVNDToUSD(double vndAmount) async {
    const apiKey = "9d18cee183b9ecf318d5eb21"; // 🔁 Thay bằng API key của bạn
    final formattedAmount = vndAmount.toStringAsFixed(2);
    final url = Uri.parse(
        'https://v6.exchangerate-api.com/v6/$apiKey/pair/VND/USD/$formattedAmount');

    try {
      final response = await http.get(url);

      if (response.statusCode != 200) {
        print("API lỗi: ${response.statusCode} - ${response.body}");
        return null;
      }

      final data = json.decode(response.body);
      if (data['conversion_result'] == null) {
        print("Không có kết quả: $data");
        return null;
      }

      return (data['conversion_result'] as num).toDouble();
    } catch (e) {
      print("Currency conversion error: $e");
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
    } else {
      await _createOrderOnServer(); // cod
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
          "name_user": currentUser!.name,
          "address": currentUser!.address,
          "phone": currentUser!.phone,
          "method": _selectedMethod,
          "cart": widget.cartItems.map((item) => {
            "id_product": item.idProduct,
            "quantity": item.quantity,
            "price": item.price,
          }).toList(),
          "isBuyNow": widget.cartItems.length == 1 && widget.cartItems.first.idCart == 0, // 👈 Mẹo phân biệt
        }),

      );

      final result = json.decode(response.body);
      if (result['status'] == true) {
        _showSnack("Đặt hàng thành công");

        // Chờ một chút để hiện snackbar xong mới chuyển
        await Future.delayed(const Duration(seconds: 1));

        Navigator.pushAndRemoveUntil(
          context,
          MaterialPageRoute(builder: (_) => const MainScreen(initialIndex: 3)),
              (route) => false,
        );

      }
      else {
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
            Text('Nội dung: Thanh toán - ${currentUser?.name ?? ""}'),
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
            padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
            color: Colors.white,
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
                      style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold),
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
                  const Text("Thông tin sản phẩm",
                      style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold)),
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
                                    Text(item.name,
                                        style: const TextStyle(fontWeight: FontWeight.bold)),
                                    Text("SL: ${item.quantity}"),
                                    Text("Giá: ${item.price}đ"),
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
                  const Text("Thông tin khách hàng",
                      style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold)),
                  const SizedBox(height: 8),
                  sectionContainer(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text("Họ tên: ${user.name}"),
                        Text("SĐT: ${user.phone}"),
                        const SizedBox(height: 4),
                        Row(
                          mainAxisAlignment: MainAxisAlignment.spaceBetween,
                          children: [
                            Expanded(child: Text("Địa chỉ: ${user.address}")),
                            const Icon(Icons.edit, size: 20, color: Colors.grey),
                          ],
                        ),
                      ],
                    ),
                  ),
                  const SizedBox(height: 16),
                  const Text("Phương thức thanh toán",
                      style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold)),
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
