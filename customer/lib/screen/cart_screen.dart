import 'package:flutter/material.dart';
import '../Widget/Header.dart';
import 'payment_screen.dart'; // Import PaymentScreen

class CartScreen extends StatefulWidget {
  const CartScreen({super.key});

  @override
  State<CartScreen> createState() => _CartScreenState();
}

class _CartScreenState extends State<CartScreen> {
  List<Map<String, dynamic>> _cartItems = [
    {
      'id': 1,
      'name': 'Bánh mì thịt',
      'quantity': 2,
      'price': 15000,
      'image': 'https://th.bing.com/th/id/OIP.IVbCUBe9BnqBgT36EG3H5QHaHa?w=186&h=186'
    },
    {
      'id': 2,
      'name': 'Cơm gà xối mỡ',
      'quantity': 1,
      'price': 30000,
      'image': 'https://th.bing.com/th/id/OIP.IVbCUBe9BnqBgT36EG3H5QHaHa?w=186&h=186'
    },
    {
      'id': 3,
      'name': 'Trà sữa trân châu',
      'quantity': 3,
      'price': 25000,
      'image': 'https://th.bing.com/th/id/OIP.IVbCUBe9BnqBgT36EG3H5QHaHa?w=186&h=186'
    },
  ];

  double get total => _cartItems.fold(
      0, (sum, item) => sum + item['price'] * item['quantity']);

  void _removeItem(int index) {
    setState(() {
      _cartItems.removeAt(index);
    });
  }

  @override
  Widget build(BuildContext context) {
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
    'Giỏ hàng',
    style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold),
    ),
    ),
    ),
    const SizedBox(width: 48),
    ],
    ),
    ),

    // Danh sách sản phẩm
    Expanded(
    child: SingleChildScrollView(
    padding: const EdgeInsets.all(16),
    child: Container(
    padding: const EdgeInsets.all(16),
    decoration: BoxDecoration(
    color: Colors.white,
    borderRadius: BorderRadius.circular(12),
    boxShadow: const [
    BoxShadow(color: Colors.black12, blurRadius: 6, offset: Offset(0, 2)),
    ],
    ),
    child: Column(
    children: [
    ..._cartItems.asMap().entries.map((entry) {final index = entry.key;
    final item = entry.value;
    return Padding(
      padding: const EdgeInsets.only(bottom: 16),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          ClipRRect(
            borderRadius: BorderRadius.circular(8),
            child: Image.network(
              item['image'],
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
                Text(item['name'],
                    maxLines: 1,
                    overflow: TextOverflow.ellipsis,
                    style: const TextStyle(fontSize: 16, fontWeight: FontWeight.bold)),
                const SizedBox(height: 4),
                Text("Số lượng: ${item['quantity']}"),
                Text("Giá: ${item['price']}đ"),
              ],
            ),
          ),
          IconButton(
            icon: const Icon(Icons.delete, color: Colors.red),
            iconSize: 24,
            padding: EdgeInsets.zero,
            constraints: const BoxConstraints(),
            onPressed: () => _removeItem(index),
          ),
        ],
      ),
    );
    }).toList(),

      const Divider(),

      Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          const Text("Tổng cộng:",
              style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold)),
          Text("${total.toStringAsFixed(0)}đ",
              style: const TextStyle(fontSize: 16, color: Colors.green)),
        ],
      ),
    ],
    ),
    ),
    ),
    ),

        // Nút Đặt hàng → sang trang thanh toán
        Padding(
        padding: const EdgeInsets.all(16),
    child: SizedBox(
    width: double.infinity,
    height: 50,
    child: ElevatedButton(
    onPressed: () {
    Navigator.push(context,
      MaterialPageRoute(
        builder: (context) => PaymentScreen(cartItems: _cartItems),
      ),
    );
    },
      style: ElevatedButton.styleFrom(
        backgroundColor: Colors.orange,
        shape: RoundedRectangleBorder(
          borderRadius: BorderRadius.circular(8),
        ),
      ),
      child: const Text(
        'Đặt hàng',
        style: TextStyle(fontSize: 18, color: Colors.white),
      ),
    ),
    ),
        ),
    ],
    ),
    );
  }
}