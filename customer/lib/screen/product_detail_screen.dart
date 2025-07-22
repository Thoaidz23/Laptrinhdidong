  import 'package:flutter/material.dart';
  import 'package:html/parser.dart' as html_parser;
  import '../model/product.dart';
  import '../widget/MenuBar.dart';
  import '../widget/header.dart';
  import '../services/api_service.dart';
  import '../model/user.dart';

  class ProductDetailScreen extends StatefulWidget {
    final Product product;
    const ProductDetailScreen({super.key, required this.product});

    @override
    State<ProductDetailScreen> createState() => _ProductDetailScreenState();
  }

  class _ProductDetailScreenState extends State<ProductDetailScreen> {
    late List<String> imageUrls;
    int _currentPage = 0;
    int _quantity = 1;
    late PageController _pageController;

    @override
    void initState() {

      super.initState();
      final product = widget.product;
      // Nếu chỉ có 1 ảnh:
      imageUrls = [
        '${ApiService.baseUrl.replaceAll("/api", "")}/adminweb/admin/quanlysanpham/uploads/${product.image}'
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
            const SizedBox(height: 10),
            Padding(
              padding: const EdgeInsets.symmetric(horizontal: 0),
              child: Row(
                children: [
                  IconButton(
                    icon: const Icon(Icons.arrow_back),
                    onPressed: () {
                      Navigator.pop(context);
                    },
                  ),
                  const SizedBox(width: 4),
                  const Text(
                    "Rau củ",
                    style: TextStyle(fontSize: 22, fontWeight: FontWeight.bold),
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
                          '${product.price.toStringAsFixed(0)}đ',
                          style:
                          const TextStyle(fontSize: 25, color: Colors.green),
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
                              _quantity++;
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
                        final userId = currentUser?.id; // ⚠️ Lấy từ user đã đăng nhập (tạm hardcoded)

                        if (userId == null) {
                          ScaffoldMessenger.of(context).showSnackBar(
                            const SnackBar(content: Text('Bạn cần đăng nhập để thêm vào giỏ hàng')),
                          );
                          return;
                        }
                        final product = widget.product;

                        final success = await ApiService.addToCart(
                          userId,
                          product.id,
                          _quantity,     // từ số lượng đã chọn
                          product.price,
                        );

                        if (success) {
                          ScaffoldMessenger.of(context).showSnackBar(
                            const SnackBar(content: Text('Đã thêm vào giỏ hàng')),
                          );
                        } else {
                          ScaffoldMessenger.of(context).showSnackBar(
                            const SnackBar(content: Text('Lỗi khi thêm vào giỏ hàng')),
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
                    ScaffoldMessenger.of(context).showSnackBar(
                      const SnackBar(content: Text('Mua ngay thành công (demo)')),
                    );
                  },
                  label: const Text('Mua ngay',
                      style: TextStyle(fontSize: 18, color: Colors.white)),
                  style: ElevatedButton.styleFrom(
                    backgroundColor: Colors.redAccent,
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
