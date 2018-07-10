#実行例：echo 4822298930 | python reviewsofasin.py >> reviews.csv

import requests
import time
import sys
from bs4 import BeautifulSoup

amazon = 'https://www.amazon.co.jp'

def getReviews(nextPageUrl):
    sys.stderr.write(nextPageUrl + '\n')

    response = requests.get(nextPageUrl)
    soup = BeautifulSoup(response.content, 'html.parser')
    reviews = soup.find_all(attrs={'data-hook':'review'})

    for review in reviews:
        reviewUrl = '{}/gp/customer-reviews/{}'.format(amazon, review.get('id'))
        rating = int(review.find(attrs={'data-hook':'review-star-rating'}).text.replace('5つ星のうち', '').replace('.0', ''))
        title = review.find(attrs={'data-hook':'review-title'}).text.replace('"', '\"')
        tmp = review.find('a', attrs={'data-hook':'review-author'})
        authorUrl = amazon + tmp.get('href')
        authorName = tmp.text
        date = review.find(attrs={'data-hook':'review-date'}).text.replace('年', '/').replace('月', '/').replace('日', '')
        body = review.find(attrs={'data-hook':'review-body'}).text.replace('"', '\"')
        badge = 'Amazonで購入' if review.find(attrs={'data-hook':'avp-badge'}) is not None else ''
        tmp = review.find(attrs={'data-hook':'helpful-vote-statement'})
        vote = 0 if tmp is None else int(tmp.text.replace('人のお客様がこれが役に立ったと考えています', ''))
        print('"{}","{}",{},{},{},"{}","{}","{}","{}","{}"'.format(asin, reviewUrl, rating, vote, date, badge, authorUrl, authorName, title, body))
    
    nextPage = soup.select_one('.a-last a')
    if nextPage is not None:
        return nextPage.get('href')
    else:
        return None

for line in sys.stdin:
    asin = line.strip()
    nextPageUrl = '{}/product-reviews/{}/pageNumber=1'.format(amazon, asin)
    nextPageUrl = getReviews(nextPageUrl)
    while nextPageUrl is not None:
        time.sleep(3)
        nextPageUrl = getReviews(amazon + nextPageUrl)
