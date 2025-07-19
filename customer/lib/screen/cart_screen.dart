import 'package:flutter/material.dart';
import '../services/api_service.dart';
import '../model/cart_item.dart';
import '../model/user.dart';

class CartScreen extends StatefulWidget {
  final User currentUser;

  const CartScreen({super.key, required this.currentUser});

  @override
  State<CartScreen> createState() => _CartScreenState();
}

class _CartScreenState extends State<CartScreen> {
  List<CartItem> _cartItems = [];
  bool _isLoading = true;

  @override
  void initState() {
    super.initState();
    _loadCart();
  }

  Future<void> _loadCart() async {
    try {
      final items = await ApiService.getCart(widget.currentUser.id); // ✅ dùng đúng hàm
      setState(() {
        _cartItems = items;
        _isLoading = false;
      });
    } catch (e) {
      print("Failed to load cart: $e");
    }
  }

  Future<void> _removeItem(int productId) async {
    await ApiService.deleteCartItem(widget.currentUser.id, productId);
    _loadCart();
  }

  @override
  Widget build(BuildContext context) {
    double total = _cartItems.fold(0, (sum, item) => sum + (item.price * item.quantity));

    return Scaffold(
      appBar: AppBar(
        title: const Text("Giỏ hàng"),
      ),
      body: _isLoading
          ? const Center(child: CircularProgressIndicator())
          : _cartItems.isEmpty
          ? const Center(child: Text("Giỏ hàng trống."))
          : Column(
        children: [
          Expanded(
            child: ListView.builder(
              itemCount: _cartItems.length,
              itemBuilder: (context, index) {
                final item = _cartItems[index];
                return ListTile(
                  title: Text("Món ${item.idProduct}"),
                  subtitle: Text("SL: ${item.quantity} | Giá: ${item.price}đ"),
                  trailing: IconButton(
                    icon: const Icon(Icons.delete, color: Colors.red),
                    onPressed: () => _removeItem(item.idProduct),
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
                const Text("Tổng cộng:", style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
                Text("${total.toStringAsFixed(0)}đ", style: const TextStyle(fontSize: 18)),
              ],
            ),
          ),
        ],
      ),
    );
  }
}
