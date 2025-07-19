import 'package:flutter/material.dart';
import '../model/product.dart';
import '../services/api_service.dart';
import '../screen/product_detail_screen.dart';

class ProductGrid extends StatefulWidget {
  const ProductGrid({super.key});

  @override
  State<ProductGrid> createState() => _ProductGridState();
}

class _ProductGridState extends State<ProductGrid> {
  late Future<List<Product>> _productsFuture;

  // Danh sách 4 URL ảnh khác nhau
  final imageUrls = [
    'https://th.bing.com/th/id/OIP.GI7Tt7hBs07MsvkgYaMa3AHaHa?w=194&h=194&c=7&r=0&o=7&dpr=1.6&pid=1.7&rm=3',
    'https://th.bing.com/th/id/OIP.G8FUCxYIkXuTamNNfORYggHaHa?w=194&h=194&c=7&r=0&o=7&dpr=1.6&pid=1.7&rm=3',
    'https://th.bing.com/th/id/OIP.LStbJVkpR_WMsNpbDJ_LtwHaHa?w=193&h=194&c=7&r=0&o=7&dpr=1.6&pid=1.7&rm=3',
    'https://th.bing.com/th/id/OIP.23Ev93_vHW2OC6l0ldQS2gHaHa?w=163&h=180&c=7&r=0&o=7&dpr=1.6&pid=1.7&rm=3',
  ];

  @override
  void initState() {
    super.initState();
    _productsFuture = ApiService.fetchProducts();
  }

  @override
  Widget build(BuildContext context) {
    return FutureBuilder<List<Product>>(
      future: _productsFuture,
      builder: (context, snapshot) {
        if (snapshot.connectionState == ConnectionState.waiting) {
          return const Center(child: CircularProgressIndicator());
        } else if (snapshot.hasError) {
          return const Center(child: Text('Lỗi khi tải sản phẩm'));
        } else if (!snapshot.hasData || snapshot.data!.isEmpty) {
          return const Center(child: Text('Không có sản phẩm'));
        }

        final products = snapshot.data!;
        return GridView.builder(
          padding: const EdgeInsets.all(8),
          gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
            crossAxisCount: 2,
            crossAxisSpacing: 8,
            mainAxisSpacing: 8,
            childAspectRatio: 0.75,
          ),
          itemCount: products.length,
          itemBuilder: (context, index) {
            final product = products[index];
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
                          imageUrls[index % imageUrls.length],
                          width: double.infinity,
                          fit: BoxFit.cover,
                          errorBuilder: (context, error, stackTrace) =>
                          const Icon(Icons.error),
                        ),
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
                            '${product.price.toStringAsFixed(0)} đ',
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
    );
  }
}
