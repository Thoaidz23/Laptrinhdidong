import 'package:flutter/material.dart';
import '../widget/header.dart';
import '../model/product.dart';
import 'product_detail_screen.dart';
import '../services/api_service.dart';
import 'package:html/parser.dart' as html_parser;
import '../Widget/MenuBar.dart';

class CategoryScreen extends StatefulWidget {
  final String? selectedCategoryName;
  const CategoryScreen({super.key, this.selectedCategoryName});

  @override
  State<CategoryScreen> createState() => _CategoryScreenState();
}

class _CategoryScreenState extends State<CategoryScreen> {

  List<Product> products = [];
  bool isLoading = true;

  final List<Map<String, dynamic>> categories = [
    {'id': 0, 'name': 'Tất cả'},
    {'id': 1, 'name': 'Snack'},
    {'id': 2, 'name': 'Bánh'},
    {'id': 3, 'name': 'Kẹo'},
    {'id': 4, 'name': 'Thức uống đóng hộp'},
    {'id': 5, 'name': 'Đồ ăn đóng hộp'},
    {'id': 6, 'name': 'Đồ ăn liền'},
  ];

  int selectedCategoryId = 0;


  @override
  void didChangeDependencies() {
    super.didChangeDependencies();

    final args = ModalRoute.of(context)?.settings.arguments as Map<String, dynamic>?;

    if (args != null && args['name'] != null) {
      final found = categories.firstWhere(
              (c) => c['name'] == args['name'],
          orElse: () => {'id': 0});
      selectedCategoryId = found['id'];
    }
    fetchData();
  }
  @override
  void initState() {
    super.initState();

    if (widget.selectedCategoryName != null) {
      final found = categories.firstWhere(
            (c) => c['name'] == widget.selectedCategoryName,
        orElse: () => {'id': 0},
      );
      selectedCategoryId = found['id'];
    }

    fetchData();
  }


  Future<void> fetchData() async {
    setState(() => isLoading = true);
    try {
      final fetched = await ApiService.fetchProducts(categoryId: selectedCategoryId == 0 ? null : selectedCategoryId);
      setState(() => products = fetched);
    } catch (e) {
      print("Lỗi tải sản phẩm: $e");
    } finally {
      setState(() => isLoading = false);

    }
  }

  @override
  Widget build(BuildContext context) {

    return Scaffold(
      backgroundColor: const Color(0xFFF5F5F5),
      body: Column(children: [
        const Header(),

        // Tiêu đề với nút quay lại
        Container(
          padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
          decoration: const BoxDecoration(
            color: Colors.orange,
            boxShadow: [BoxShadow(color: Colors.black12, blurRadius: 4)],
          ),
          child: Row(
            children: [
              IconButton(
                icon: const Icon(Icons.arrow_back, color: Colors.white),
                onPressed: () => Navigator.pop(context),
              ),
              const Expanded(
                child: Center(
                  child: Text(
                    'Danh mục sản phẩm',
                    style: TextStyle(
                      fontSize: 20,
                      fontWeight: FontWeight.bold,
                      color: Colors.white,
                    ),
                  ),
                ),
              ),
              const SizedBox(width: 48),
            ],
          ),
        ),

        // Thanh lọc danh mục
        Container(
          height: 42,
          margin: const EdgeInsets.only(top: 8),
          child: SingleChildScrollView(
            scrollDirection: Axis.horizontal,
            child: Row(
              children: categories.map((cat) {
                final isActive = cat['id'] == selectedCategoryId;
                return GestureDetector(
                  onTap: () {
                    setState(() => selectedCategoryId = cat['id']);
                    fetchData();
                  },
                  child: Container(
                    padding: const EdgeInsets.symmetric(horizontal: 16),
                    alignment: Alignment.center,
                    decoration: BoxDecoration(
                      border: Border(
                        bottom: BorderSide(
                          color: isActive ? Colors.orange : Colors.transparent,
                          width: 3,
                        ),
                      ),
                    ),
                    child: Text(
                      cat['name'],
                      style: TextStyle(
                        fontSize: 13,
                        fontWeight:
                        isActive ? FontWeight.bold : FontWeight.normal,
                        color: isActive ? Colors.orange : Colors.black,
                      ),
                    ),
                  ),
                );
              }).toList(),
            ),
          ),
        ),

        const SizedBox(height: 10),

        // Danh sách sản phẩm
        Expanded(
          child: isLoading
              ? const Center(child: CircularProgressIndicator())
              : ListView.builder(
              padding: const EdgeInsets.all(16),
              itemCount: products.length,
              itemBuilder: (context, index) {
                final p = products[index];
                return GestureDetector(
                  onTap: () {
                    Navigator.push(
                      context,
                      MaterialPageRoute(
                        builder: (_) => ProductDetailScreen(product: p),
                      ),
                    );
                  },
                  child: Container(
                    margin: const EdgeInsets.only(bottom: 12),
                    padding: const EdgeInsets.all(12),
                    decoration: BoxDecoration(
                      color: Colors.white,
                      borderRadius: BorderRadius.circular(12),
                      boxShadow: const [
                        BoxShadow(
                          color: Colors.black12,
                          blurRadius: 6,
                          offset: Offset(0, 2),
                        ),
                      ],
                    ),
                    child: Row(
                      children: [
                        ClipRRect(
                          borderRadius: BorderRadius.circular(8),
                          child: Image.network(
                            p.imageUrl,
                            width: 60,
                            height: 60,
                            fit: BoxFit.cover,
                            errorBuilder: (context, error, stackTrace) => const Icon(Icons.broken_image),
                          ),
                        ),
                        const SizedBox(width: 12),
                        Expanded(
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              Text(p.name, style: const TextStyle(fontSize: 16, fontWeight: FontWeight.bold)),
                              const SizedBox(height: 4),
                              Text(
                                p.content.isNotEmpty
                                    ? html_parser.parse(p.content).documentElement?.text ?? ''
                                    : '',
                                style: const TextStyle(fontSize: 13),
                              ),
                              const SizedBox(height: 4),
                              Text("Giá: ${p.price.toInt()}đ", style: const TextStyle(color: Colors.green)),
                            ],
                          ),
                        ),
                      ],
                    ),
                  ),
                );

              }),
        ),
      ]),
    );
  }
}