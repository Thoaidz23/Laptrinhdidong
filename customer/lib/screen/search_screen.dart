import 'package:flutter/material.dart';
import '../widget/ProductGrid.dart';
import '../services/api_service.dart';
import '../model/product.dart';
import 'product_detail_screen.dart';

import 'package:intl/intl.dart';
final currencyFormatter = NumberFormat("#,###", "vi_VN");

class SearchScreen extends StatefulWidget {
  final String keyword;
  const SearchScreen({super.key, required this.keyword});

  @override
  State<SearchScreen> createState() => _SearchScreenState();
}

class _SearchScreenState extends State<SearchScreen> {
  late Future<List<Product>> _filteredProducts;

  @override
  void initState() {
    super.initState();
    _filteredProducts = _searchProducts(widget.keyword);
  }

  Future<List<Product>> _searchProducts(String keyword) async {
    final allProducts = await ApiService.fetchProducts();
    return allProducts
        .where((product) =>
        product.name.toLowerCase().contains(keyword.toLowerCase()))
        .toList();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
        appBar: AppBar(
        title: Text('Kết quả tìm kiếm: "${widget.keyword}"'),
    backgroundColor: Colors.orange,
    ),
    body: FutureBuilder<List<Product>>(
    future: _filteredProducts,
    builder: (context, snapshot) {
    if (snapshot.connectionState == ConnectionState.waiting) {
    return const Center(child: CircularProgressIndicator());
    } else if (snapshot.hasError) {
    return const Center(child: Text('Lỗi khi tải dữ liệu'));
    } else if (!snapshot.hasData || snapshot.data!.isEmpty) {
    return const Center(child: Text('Không tìm thấy sản phẩm nào'));
    }

    final results = snapshot.data!;

    return GridView.builder(
    padding: const EdgeInsets.all(8),
    gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
    crossAxisCount: 2,
    crossAxisSpacing: 8,
    mainAxisSpacing: 8,
    childAspectRatio: 0.75,
    ),
    itemCount: results.length,
    itemBuilder: (context, index) {
    final product = results[index];
    return GestureDetector(
      onTap: () {
        Navigator.push(
          context,
          MaterialPageRoute(
            builder: (_) => ProductDetailScreen(product: product),
          ),
        );
      },

      child: Card(
    elevation: 4,
    shape: RoundedRectangleBorder(
    borderRadius: BorderRadius.circular(10),
    ),
    child: Column(
    crossAxisAlignment: CrossAxisAlignment.start,
    children: [
    Expanded(
    child: ClipRRect(
    borderRadius: const BorderRadius.vertical(top: Radius.circular(10)),
    child: Image.network(
    product.imageUrl,
    width: double.infinity,
    fit: BoxFit.cover,
    errorBuilder: (context, error, stackTrace) => const Icon(Icons.error),),
    ),
    ),
      Padding(
        padding: const EdgeInsets.all(8.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              product.name,
              style: const TextStyle(fontSize: 16, fontWeight: FontWeight.bold),
            ),
            const SizedBox(height: 4),
            Text(
              '${NumberFormat('#,###', 'vi_VN').format(product.price)} đ',
              style: const TextStyle(color: Colors.orange),
            ),
          ],
        ),
      ),
    ],
    ),
    ),
    );
    },
    );
    },
    ),
    );
  }
}