  import 'package:flutter/material.dart';
  import 'package:html/parser.dart' as html_parser;
  import '../model/product.dart';
  import '../widget/MenuBar.dart';
  import '../widget/header.dart';
  import '../services/api_service.dart';

  class ProductDetailScreen extends StatefulWidget {
    final Product product;

    const ProductDetailScreen({super.key, required this.product});

    @override
    State<ProductDetailScreen> createState() => _ProductDetailScreenState();
  }

  class _ProductDetailScreenState extends State<ProductDetailScreen> {
    late List<String> imageUrls;
    int _currentPage = 0;
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
          child: Row(
            children: [
              Expanded(
                child: ElevatedButton.icon(
                  onPressed: () {
                    ScaffoldMessenger.of(context).showSnackBar(
                      const SnackBar(
                          content: Text('Mua ngay thành công (demo)')),
                    );
                  },
                  icon: const Icon(Icons.shopping_cart_checkout),
                  label: const Text('Mua ngay',
                      style: TextStyle(fontSize: 16, color: Colors.white)),
                  style: ElevatedButton.styleFrom(
                    backgroundColor: Colors.redAccent,
                    shape: RoundedRectangleBorder(
                      borderRadius: BorderRadius.circular(8),
                    ),
                  ),
                ),
              ),
              const SizedBox(width: 16),
              Expanded(
                child: ElevatedButton.icon(
                  onPressed: () {
                    ScaffoldMessenger.of(context).showSnackBar(
                      const SnackBar(
                          content: Text('Đã thêm vào giỏ hàng (demo)')),
                    );
                  },
                  icon: const Icon(Icons.add_shopping_cart),
                  label: const Text('Thêm vào giỏ',
                      style: TextStyle(fontSize: 16, color: Colors.white)),
                  style: ElevatedButton.styleFrom(
                    backgroundColor: Colors.green,
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
