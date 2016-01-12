# -*- coding: utf-8 -*-
require 'nokogiri'
require 'open-uri'

def get_nokogiri_doc(url)
	begin
		html = open(url)
	rescue OpenURI::HTTPError
		return
	end
	Nokogiri::HTML(html.read, nil, 'utf-8')
end

def has_next_page?(doc)
	doc.xpath("//*[@id='main']/ul/a").each {|element|
		return true if element.text == "次へ"
	}
	return false
end

def get_daily_data(doc)
	doc.xpath("//table[@class='boardFin yjSt marB6']/tr").each {|element|
		# 日付行および株式分割告知を回避
		if element.children[0].node_name == "td" && element.children[1][:class] != "through"

		# 日付
		day = element.children[0].text.gsub(/年/,'/').gsub(/月/,'/').gsub(/日/,'')

		# 始値
		open_price = element.children[1].text.gsub(/,/,'')

		# 高値
		hight_price = element.children[2].text.gsub(/,/,'')

		# 安値
		low_price = element.children[3].text.gsub(/,/,'')

		# 終値
		closing_price = element.children[4].text.gsub(/,/,'')

		# 出来高
		volume = element.children[5].text.gsub(/,/,'')

		puts "#{day},#{open_price},#{hight_price},#{low_price},#{closing_price},#{volume}"
		end
	}
end

# 証券コード（コマンドライン引数）
code=ARGV[0]

# 検索日
sy=ARGV[1]
sm=ARGV[2]
sd=ARGV[3]
ey=ARGV[4]
em=ARGV[5]
ed=ARGV[6]
start_url="http://info.finance.yahoo.co.jp/history/?sy=#{sy}&sm=#{sm}&sd=#{sd}&ey=#{ey}&em=#{em}&ed=#{ed}&tm=d&code=#{code}"
num=1
puts "日付,始値,高値,安値,終値,出来高"
loop {
	url = "#{start_url}&p=#{num}"
	doc = get_nokogiri_doc(url)
	get_daily_data(doc)
	break if !has_next_page?(doc)
	num = num+1
}
