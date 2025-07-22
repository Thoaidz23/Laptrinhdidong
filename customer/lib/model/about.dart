class FooterItem {
  final int id;
  final String title;
  final String content;

  FooterItem({required this.id, required this.title, required this.content});

  factory FooterItem.fromJson(Map<String, dynamic> json) {
    return FooterItem(
      id: int.parse(json['id_footer'].toString()),
      title: json['title'],
      content: json['content'],
    );
  }
}
