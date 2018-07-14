#使用例：echo AFBQH3A3M6XHMLQ6GAZW5ERC3I6Q | python profile.py >> profiles.csv
from bs4 import BeautifulSoup
import re
import sys

amazon = 'https://www.amazon.co.jp'

for line in sys.stdin:
    userId = line.strip()

    with open(userId + '.html', 'r') as file:
        soup = BeautifulSoup(file, 'html.parser')

        authorName = soup.select_one('.name-container').text
        authorUrl = '{}/gp/profile/amzn1.account.{}/'.format(amazon, userId)
        reviews = soup.select('.reviews-card')
        sys.stderr.write('{} reviews\n'.format(len(reviews)))

        for review in reviews:
            date = review.select_one('.a-profile-descriptor').text
            date = re.sub('.*?([0-9]*)年([0-9]*)月([0-9]*)日', r'\1/\2/\3', date)
            rating = int(review.select_one('.a-icon-alt').text.replace('星5つのうち', ''))
            badge = 'Amazonで購入' if review.select_one('.profile-at-review-badge') is not None else ''
            title = review.select_one('.profile-at-review-title').text.replace('"', '\"')
            body = review.select_one('p').text.replace('"', '\"')
            reviewUrl = amazon + review.select_one('.profile-at-review-link').get('href')
            tmp = review.select_one('.profile-at-review-helpful-message-text')
            vote = 0 if tmp is None else int(tmp.text.replace('参考になった投票 ', ''))
            tmp = review.find_all('a')
            asin = '' if len(tmp) < 2 else re.sub('.*/dp/(.*)\?.*', r'\1', tmp[1].get('href'))
            print('"{}","{}",{},{},{},"{}","{}","{}","{}","{}"'.format(asin, reviewUrl, rating, vote, date, badge, authorUrl, authorName, title, body))
