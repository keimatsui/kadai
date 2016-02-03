# coding: utf-8
# 使い方：asin=4274064069; ruby reviews.rb ${asin} > ${asin}.csv
require 'open-uri'
require 'nokogiri'
require 'uri'

def get_nokogiri_doc(url, sleepTime)
  sleep sleepTime
  begin
    html = open(url)
  rescue OpenURI::HTTPError
    sTime = sleepTime * 2
    STDERR.puts "sleeping #{sTime}s..."
    return get_nokogiri_doc(url, sTime)
  end
  Nokogiri::HTML(html.read, nil, 'utf-8')
end

asin = ARGV[0]
url = "http://www.amazon.co.jp/product-reviews/#{asin}/ref=cm_cr_dp_see_all_btm?pageNumber=1"

loop {
  STDERR.puts url
  doc = get_nokogiri_doc(url, 3)

  reviews = doc.xpath('//div[@class="a-section review"]')
  reviews.each{|review|
    id = review.attr('id')
    star = review.css('.a-icon-alt').text.sub('5つ星のうち', '')
    tmp = review.search('a[@data-reftag="cm_cr_pr_rvw_rvwer"]').size
    bought = tmp == 1 ? 'Amazonで購入' : ''
    votes = review.css('.review-votes').text.gsub(/([0-9]+)人中([0-9]+)人.*/, '\1,\2')
    if votes == ''
      votes = '0,0'
    end
    puts "#{id},#{star},#{bought},#{votes}"
  }

  next_url = doc.xpath('//li[@class="a-last"]/a')
  if next_url.empty?
    break
  end
  url = URI.escape('http://www.amazon.co.jp' + next_url.attr('href'))
}
