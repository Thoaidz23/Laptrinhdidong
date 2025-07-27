import 'package:flutter/material.dart';
import '../Widget/Header.dart';
import 'payment_screen.dart';
import '../model/cart_item.dart';
import '../model/user.dart';
import '../services/api_service.dart';
import 'package:intl/intl.dart';
final currencyFormatter = NumberFormat("#,###", "vi_VN");

class CartScreen extends StatefulWidget {
  const CartScreen({super.key});

  @override
  State<CartScreen> createState() => _CartScreenState();
}

class _CartScreenState extends State<CartScreen> {
  List<CartItem> _cartItems = [];
  bool _isLoading = true;
  // Giới hạn tối đa mỗi sản phẩm

  @override
  void initState() {
    super.initState();
    _loadCart();
  }

  Future<void> _loadCart() async {
    if (currentUser == null) return;
    try {
      final items = await ApiService.getCart(currentUser!.id);
      setState(() {
        _cartItems = items;
        _isLoading = false;
      });
    } catch (e) {
      print("Error loading cart: $e");
      setState(() => _isLoading = false);
    }
  }

  double get total => _cartItems.fold(
    0,
        (sum, item) => sum + item.price * item.quantity,
  );

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xFFF5F5F5),
      body: Column(
        children: [
          const Header(),
          Container(
            width: double.infinity,
            padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
            decoration: const BoxDecoration(
              color: Colors.orange,
              boxShadow: [
                BoxShadow(color: Colors.black12, blurRadius: 4, offset: Offset(0, 2)),
              ],
            ),
            child: Row(
              children: [
                const SizedBox(width:45),
                const Expanded(
                  child: Center(
                    child: Text(
                      'Giỏ hàng',
                      style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold,color: Colors.white),
                    ),
                  ),
                ),
                const SizedBox(width: 48),
              ],
            ),
          ),
          Expanded(
            child: _isLoading
                ? const Center(child: CircularProgressIndicator())
                : _cartItems.isEmpty
                ? const Center(child: Text("Giỏ hàng trống"))
                : SingleChildScrollView(
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
                    ..._cartItems.map((item) => Padding(
                      padding: const EdgeInsets.only(bottom: 16),
                      child: Row(
                        crossAxisAlignment: CrossAxisAlignment.start,
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
                                    maxLines: 1,
                                    overflow: TextOverflow.ellipsis,
                                    style: const TextStyle(
                                        fontSize: 16, fontWeight: FontWeight.bold)),
                                const SizedBox(height: 4),
                                Row(
                                  children: [
                                    const Text("Số lượng: "),
                                    IconButton(
                                      icon: const Icon(Icons.add),
                                      onPressed: item.quantity < item.availableQuantity && item.quantity < 50
                                          ? () async {
                                        await ApiService.updateCart(
                                          currentUser!.id,
                                          item.idProduct,
                                          item.quantity + 1,
                                        );
                                        await _loadCart();
                                      }
                                          : () {
                                        ScaffoldMessenger.of(context).showSnackBar(
                                          SnackBar(content: Text('Chỉ có thể mua tối đa ${item.availableQuantity > 50 ? 50 : item.availableQuantity} sản phẩm.')),
                                        );
                                      },
                                    ),
                                    Text('${item.quantity}', style: const TextStyle(fontSize: 16)),
                                    IconButton(
                                      icon: const Icon(Icons.add),
                                      onPressed: () async {
                                        await ApiService.updateCart(
                                            currentUser!.id, item.idProduct, item.quantity + 1);
                                        await _loadCart();
                                      },
                                    ),
                                  ],
                                ),

                                Text(
                                  "Giá: ${NumberFormat('#,###', 'vi_VN').format(item.price)} đ",
                                ),
                              ],
                            ),
                          ),
                          IconButton(
                            icon: Icon(Icons.delete),
                            onPressed: () async {
                              await ApiService.deleteCartItem(currentUser!.id, item.idProduct);
                              await _loadCart(); // Reload lại sau khi xóa
                            },

                          ),
                        ],
                      ),
                    )),
                    const Divider(),
                    Row(
                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                      children: [
                        const Text(
                          "Tổng cộng:",
                          style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold),
                        ),
                        Text(
                          "${NumberFormat('#,###', 'vi_VN').format(total)} đ",
                          style: const TextStyle(fontSize: 16, color: Colors.green),
                        ),
                      ],
                    ),
                  ],
                ),
              ),
            ),
          ),
          Padding(
            padding: const EdgeInsets.all(16),
            child: SizedBox(
              width: double.infinity,
              height: 50,
              child: ElevatedButton(
                onPressed: () {
                  if (_cartItems.isEmpty) return;

                  Navigator.push(
                    context,
                    MaterialPageRoute(
                      builder: (context) => PaymentScreen(cartItems: _cartItems),
                    ),
                  );
                },
                style: ElevatedButton.styleFrom(
                  backgroundColor: Colors.red,
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
