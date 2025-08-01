  import 'package:flutter/material.dart';
  import 'package:html/parser.dart' as html_parser;
  import '../model/product.dart';
  import '../widget/MenuBar.dart';
  import '../widget/header.dart';
  import '../services/api_service.dart';
  import '../model/user.dart';
  import '../model/cart_item.dart';
  import 'payment_screen.dart';
  import 'package:intl/intl.dart';
  import 'main_screen.dart';

  final currencyFormatter = NumberFormat("#,###", "vi_VN");

  class ProductDetailScreen extends StatefulWidget {
    final Product product;
    const ProductDetailScreen({super.key, required this.product});

    @override
    State<ProductDetailScreen> createState() => _ProductDetailScreenState();
  }

  final Map<int, String> categoryNames = {
    1: 'Snack',
    2: 'Bánh',
    3: 'Kẹo',
    4: 'Thức uống đóng hộp',
    5: 'Đồ ăn đóng hộp',
    6: 'Đồ ăn liền',
  };


  class _ProductDetailScreenState extends State<ProductDetailScreen> {
    late List<String> imageUrls;
    int _currentPage = 0;
    int _quantity = 1;
    late PageController _pageController;

    @override
    void initState() {
      super.initState();
      final product = widget.product;

      print('📦 Sản phẩm: ${product.name}');
      print('🖼️ Ảnh đại diện: ${product.imageUrl}');
      if (product.images.isNotEmpty) {
        for (var img in product.images) {
          print('🖼️ Ảnh chi tiết: ID = ${img.id}, URL = ${img.fullUrl}');
        }
      }

      // ✅ Thêm ảnh đại diện vào đầu, rồi nối ảnh chi tiết
      imageUrls = [
        product.imageUrl,
        ...product.images.map((img) => img.fullUrl),
      ];

      _pageController = PageController(initialPage: _currentPage);
    }

    void _goToPage(int index) {
      int nextIndex = index;
      if (index < 0) {
        nextIndex = imageUrls.length - 1;
      } else if (index >= imageUrls.length) {
        nextIndex = 0;
      }

      _pageController.animateToPage(
        nextIndex,
        duration: const Duration(milliseconds: 300),
        curve: Curves.easeInOut,
      );

      setState(() {
        _currentPage = nextIndex;
      });
    }

    @override
    void dispose() {
      _pageController.dispose();
      super.dispose();
    }

    @override
    Widget build(BuildContext context) {
      final product = widget.product;

      return Scaffold(
        backgroundColor: Colors.white,
        body: Column(
          children: [
            const Header(),


            Container(
              width: double.infinity,
              color: Colors.orange,
              padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 8),
              child: Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  Row(
                    children: [
                      IconButton(
                        icon: const Icon(Icons.arrow_back, color: Colors.white),
                        onPressed: () {
                          Navigator.pop(context);
                        },
                      ),
                      const SizedBox(width: 4),
                      Text(
                        categoryNames[product.id_category_product] ?? 'Danh mục',
                        style: const TextStyle(
                          fontSize: 22,
                          fontWeight: FontWeight.bold,
                          color: Colors.white,
                        ),
                      ),
                    ],
                  ),
                  IconButton(
                    icon: const Icon(Icons.shopping_cart, color: Colors.white),
                    onPressed: () {
                      if (currentUser == null) {
                        ScaffoldMessenger.of(context).showSnackBar(
                          const SnackBar(content: Text('Vui lòng đăng nhập để xem giỏ hàng')),
                        );
                        return;
                      }

                      Navigator.pushAndRemoveUntil(
                        context,
                        MaterialPageRoute(
                          builder: (_) => const MainScreen(initialIndex: 2),
                        ),
                            (route) => false,
                      );
                    },
                  ),
                ],
              ),
            ),

            Expanded(
              child: ListView(
                padding: EdgeInsets.zero,
                children: [
                  SizedBox(
                    height: 300,
                    child: Stack(
                      children: [
                        PageView.builder(
                          controller: _pageController,
                          itemCount: imageUrls.length,
                          onPageChanged: (index) {
                            setState(() {
                              _currentPage = index;
                            });
                          },
                          itemBuilder: (context, index) {
                            return Container(
                              padding: const EdgeInsets.all(10),
                              child: ClipRRect(
                                borderRadius: BorderRadius.circular(12),
                                child: Image.network(
                                  imageUrls[index],
                                  width: double.infinity,
                                  fit: BoxFit.contain, // 👈 giúp hình nằm gọn trong khung
                                  errorBuilder: (context, error, stackTrace) => const Center(
                                    child: Icon(Icons.broken_image, size: 100, color: Colors.grey),
                                  ),
                                ),
                              ),
                            );
                          },
                        ),
                        Positioned(
                          left: 8,
                          top: 0,
                          bottom: 0,
                          child: GestureDetector(
                            onTap: () => _goToPage(_currentPage - 1),
                            child: Container(
                              width: 40,
                              alignment: Alignment.center,
                              color: Colors.transparent,
                              child: Icon(Icons.chevron_left,
                                  size: 40,
                                  color: Colors.black.withOpacity(0.6)),
                            ),
                          ),
                        ),
                        Positioned(
                          right: 8,
                          top: 0,
                          bottom: 0,
                          child: GestureDetector(
                            onTap: () => _goToPage(_currentPage + 1),
                            child: Container(
                              width: 40,
                              alignment: Alignment.center,
                              color: Colors.transparent,
                              child: Icon(Icons.chevron_right,
                                  size: 40,
                                  color: Colors.black.withOpacity(0.6)),
                            ),
                          ),
                        ),
                      ],
                    ),
                  ),
                  const SizedBox(height: 8),
                  Padding(
                    padding: const EdgeInsets.symmetric(horizontal: 16),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text(
                          product.name,
                          style: const TextStyle(
                              fontSize: 30, fontWeight: FontWeight.bold),
                        ),
                        const SizedBox(height: 4),
                        Text(
                          '${NumberFormat('#,###', 'vi_VN').format(product.price)} đ',
                          style: const TextStyle(
                            fontSize: 25,
                            color: Colors.red,
                            fontWeight: FontWeight.bold,
                          ),
                        ),
                      ],
                    ),
                  ),
                  const SizedBox(height: 20),
                  Padding(
                    padding: const EdgeInsets.symmetric(horizontal: 16),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        const Text(
                          'Thông tin món ăn',
                          style: TextStyle(
                              fontSize: 20, fontWeight: FontWeight.bold),
                        ),
                        const SizedBox(height: 10),
                        Text(
                          html_parser
                              .parse(product.content)
                              .documentElement
                              ?.text ??
                              '',
                          style:
                          const TextStyle(fontSize: 16, height: 1.5),
                        ),
                      ],
                    ),
                  ),
                  const SizedBox(height: 20),
                ],
              ),
            ),
          ],
        ),

        // ✅ Đây là nơi đặt các nút ở dưới cùng của màn hình
        bottomNavigationBar: Padding(

          padding: const EdgeInsets.all(16.0),
          child: Column(
            mainAxisSize: MainAxisSize.min,
            crossAxisAlignment: CrossAxisAlignment.stretch,
            children: [
              // Hàng chứa số lượng + nút thêm giỏ hàng
              Row(
                children: [
                  // 🔢 Nút chọn số lượng
                  Container(
                    padding: const EdgeInsets.symmetric(horizontal: 12),
                    decoration: BoxDecoration(
                      border: Border.all(color: Colors.grey),
                      borderRadius: BorderRadius.circular(8),
                    ),
                    child: Row(
                      children: [
                        IconButton(
                          icon: const Icon(Icons.remove),
                          onPressed: () {
                            setState(() {
                              if (_quantity > 1) _quantity--;
                            });
                          },
                        ),
                        Text('$_quantity', style: const TextStyle(fontSize: 18)),
                        IconButton(
                          icon: const Icon(Icons.add),
                          onPressed: () {
                            setState(() {
                              // Giới hạn không vượt quá 50 và product.quantity
                              if (_quantity < 50 && _quantity < product.quantity) {
                                _quantity++;
                              }
                            });
                          },
                        ),

                      ],
                    ),
                  ),

                  const SizedBox(width: 12),

                  // 🛒 Nút thêm giỏ hàng
                  Expanded(
                    child: ElevatedButton.icon(
                      onPressed: () async {
                        final userId = currentUser?.id;

                        if (userId == null) {
                          ScaffoldMessenger.of(context).showSnackBar(
                            const SnackBar(
                              content: Text('Bạn cần đăng nhập để thêm vào giỏ hàng'),
                              backgroundColor: Colors.red,
                              behavior: SnackBarBehavior.floating,
                              margin: EdgeInsets.only(top: 16, left: 16, right: 16),
                              shape: RoundedRectangleBorder(
                                borderRadius: BorderRadius.all(Radius.circular(8)),
                              ),
                              duration: Duration(seconds: 2),
                            ),
                          );
                          return;
                        }

                        final product = widget.product;

                        final success = await ApiService.addToCart(
                          userId,
                          product.id,
                          _quantity,
                          product.price,
                        );

                        if (success) {
                          ScaffoldMessenger.of(context).showSnackBar(
                            const SnackBar(
                              content: Text('Thêm sản phẩm vào giỏ hàng'),
                              backgroundColor: Colors.green,
                              behavior: SnackBarBehavior.floating,
                              margin: EdgeInsets.only(top: 16, left: 16, right: 16),
                              shape: RoundedRectangleBorder(
                                borderRadius: BorderRadius.all(Radius.circular(8)),
                              ),
                              duration: Duration(seconds: 2),
                            ),
                          );
                        } else {
                          ScaffoldMessenger.of(context).showSnackBar(
                            const SnackBar(
                              content: Text('❌ Lỗi khi thêm vào giỏ hàng'),
                              backgroundColor: Colors.red,
                              behavior: SnackBarBehavior.floating,
                              margin: EdgeInsets.only(top: 16, left: 16, right: 16),
                              shape: RoundedRectangleBorder(
                                borderRadius: BorderRadius.all(Radius.circular(8)),
                              ),
                              duration: Duration(seconds: 2),
                            ),
                          );
                        }
                      },


                      icon: const Icon(Icons.add_shopping_cart),
                      label: const Text('Thêm vào giỏ',
                          style: TextStyle(fontSize: 16, color: Colors.white)),
                      style: ElevatedButton.styleFrom(
                        backgroundColor: Colors.green,
                        padding: const EdgeInsets.symmetric(vertical: 14),
                        shape: RoundedRectangleBorder(
                          borderRadius: BorderRadius.circular(8),
                        ),
                      ),
                    ),
                  ),
                ],
              ),
              const SizedBox(height: 12),

              // 🔴 Nút mua ngay (to gấp đôi, nằm dưới)
              SizedBox(
                width: double.infinity,
                child: ElevatedButton.icon(
                  onPressed: () {
                    final userId = currentUser?.id;

                    if (userId == null) {
                      ScaffoldMessenger.of(context).showSnackBar(
                        const SnackBar(content: Text('Vui lòng đăng nhập để tiếp tục')),
                      );
                      return;
                    }

                    // Tạo một đối tượng CartItem tạm
                    final tempCartItem = CartItem(
                      idCart: 0, // Không cần thiết vì chưa lưu DB
                      idUser: userId,
                      idProduct: widget.product.id,
                      quantity: _quantity,
                      price: widget.product.price,
                      name: widget.product.name,
                      image: widget.product.image,
                      maxQuantity: widget.product.quantity,
                    );

                    Navigator.push(
                      context,
                      MaterialPageRoute(
                        builder: (_) => PaymentScreen(cartItems: [tempCartItem]),
                      ),
                    );
                  },

                  label: const Text('Mua ngay',
                      style: TextStyle(fontSize: 18, color: Colors.white)),
                  style: ElevatedButton.styleFrom(
                    backgroundColor: Colors.red,
                    padding: const EdgeInsets.symmetric(vertical: 18), // 👈 to hơn
                    shape: RoundedRectangleBorder(
                      borderRadius: BorderRadius.circular(8),
                    ),
                  ),
                ),
              ),
            ],
          ),
        ),

      );
    }
  }
