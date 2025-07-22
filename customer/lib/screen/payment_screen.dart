import 'package:flutter/material.dart';
import '../Widget/Header.dart';

class PaymentScreen extends StatefulWidget {
  final List<Map<String, dynamic>> cartItems;

  const PaymentScreen({super.key, required this.cartItems});

  @override
  State<PaymentScreen> createState() => _PaymentScreenState();
}

class _PaymentScreenState extends State<PaymentScreen> {
  String? _selectedMethod;

  void _onPayPressed() {
    if (_selectedMethod == null) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Vui lòng chọn phương thức thanh toán')),
      );
    } else {
      // Xử lý thanh toán ở đây
    }
  }

  @override
  Widget build(BuildContext context) {
    final cartItems = widget.cartItems;

    return Scaffold(
        backgroundColor: const Color(0xFFF5F5F5),
        body: Column(
            children: [
            const Header(),

        // AppBar
        Container(
          width: double.infinity,
          padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
          decoration: const BoxDecoration(
            color: Colors.white,
            boxShadow: [BoxShadow(color: Colors.black12, blurRadius: 4, offset: Offset(0, 2))],
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
        child: SingleChildScrollView(
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
    item['image'],
    width: 60,
    height: 60,
    fit: BoxFit.cover,
    ),
    ),const SizedBox(width: 12),
      Expanded(
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(item['name'],
                style: const TextStyle(fontWeight: FontWeight.bold)),
            Text("SL: ${item['quantity']}"),
            Text("Giá: ${item['price']}đ"),
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
            const Text("Họ tên: Trần Thanh Tòng"),
            const Text("SĐT: 0977031264"),
            const SizedBox(height: 4),
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: const [
                Expanded(
                  child: Text("Địa chỉ: Bạc Liêu"),
                ),
                Icon(Icons.edit, size: 20, color: Colors.grey),
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
            buildMethodButton("momo", Icons.phone_android, "Thanh toán bằng MoMo"),
            const SizedBox(height: 8),
            buildMethodButton("bank", Icons.account_balance, "Thanh toán ngân hàng"),
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
    onPressed: _onPayPressed,style: ElevatedButton.styleFrom(
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
            if (isSelected)
              const Icon(Icons.check_circle, color: Colors.orange)
          ],
        ),
      ),
    );
  }
}