import 'package:flutter/material.dart';
import '../services/api_service.dart';
import '../model/about.dart';
import '../Widget/Header.dart';
import 'package:flutter_html/flutter_html.dart';

class MoreScreen extends StatefulWidget {
  const MoreScreen({super.key});

  @override
  State<MoreScreen> createState() => _MoreScreenState();
}

class _MoreScreenState extends State<MoreScreen> {
  late Future<List<FooterItem>> futureFooter;

  @override
  void initState() {
    super.initState();
    futureFooter = ApiService.fetchFooterItems();
  }

  Widget buildInfoBox(FooterItem item) {
    return Container(
      width: double.infinity,
      margin: const EdgeInsets.only(bottom: 16),
      padding: const EdgeInsets.all(16),
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
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            item.title,
            style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 16),
          ),
          const SizedBox(height: 8),
          Html(
            data: item.content,
            style: {
              "body": Style(
                padding: HtmlPaddings.zero,
                margin: Margins.zero,
                fontSize: FontSize(14),
                color: Colors.black,
              ),
              "p": Style(
                margin: Margins.only(bottom: 8),
              ),
              "a": Style(
                color: Colors.blue,
                textDecoration: TextDecoration.none,
              ),
            },
          )
          
        ],
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xFFF2F2F2),
      body: Column(
        children: [
          const Header(),
          Expanded(
            child: FutureBuilder<List<FooterItem>>(
              future: futureFooter,
              builder: (context, snapshot) {
                if (snapshot.connectionState == ConnectionState.waiting) {
                  return const Center(child: CircularProgressIndicator());
                }
                if (snapshot.hasError) {
                  return Center(child: Text("Lỗi: ${snapshot.error}"));
                }

                final items = snapshot.data ?? [];

                return SingleChildScrollView(
                  padding: const EdgeInsets.all(16),
                  child: Column(
                    children: items.map((item) => buildInfoBox(item)).toList(),
                  ),
                );
              },
            ),
          ),
        ],
      ),
    );
  }
}
