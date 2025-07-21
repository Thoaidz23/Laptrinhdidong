import 'package:flutter/material.dart';

class CartScreen extends StatefulWidget {
  const CartScreen({super.key});

  @override
  State<CartScreen> createState() => _CartScreenState();
}

class _CartScreenState extends State<CartScreen> {
  List<Map<String, dynamic>> _cartItems = [
    {'id': 1, 'name': 'Bánh mì thịt', 'quantity': 2, 'price': 15000},
    {'id': 2, 'name': 'Cơm gà xối mỡ', 'quantity': 1, 'price': 30000},
    {'id': 3, 'name': 'Trà sữa trân châu', 'quantity': 3, 'price': 25000},
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
      appBar: AppBar(
        title: const Text("Giỏ hàng"),
        backgroundColor: Colors.green,
      ),
      body: _cartItems.isEmpty
          ? const Center(child: Text("Giỏ hàng trống."))
          : Column(
        children: [
          Expanded(
            child: ListView.builder(
              itemCount: _cartItems.length,
              itemBuilder: (context, index) {
                final item = _cartItems[index];
                return ListTile(
                  leading: const Icon(Icons.fastfood, color: Colors.orange),
                  title: Text(item['name']),
                  subtitle: Text("SL: ${item['quantity']} | Giá: ${item['price']}đ"),
                  trailing: IconButton(
                    icon: const Icon(Icons.delete, color: Colors.red),
                    onPressed: () => _removeItem(index),
                  ),
                );
              },
            ),
          ),
          Padding(
            padding: const EdgeInsets.all(16.0),
            child: Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                const Text("Tổng cộng:",
                    style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
                Text("${total.toStringAsFixed(0)}đ",
                    style: const TextStyle(fontSize: 18, color: Colors.green)),
              ],
            ),
          ),
        ],
      ),
    );
  }
}