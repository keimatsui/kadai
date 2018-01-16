import sys
import gensim
import MeCab
import re
import numpy as np

model = gensim.models.KeyedVectors.load_word2vec_format('entity_vector/entity_vector.model.bin', binary=True)
mecab = MeCab.Tagger('-Owakati')
r = re.compile('(.*),(.*)')

def sentenceToVector(sentence):
  words = mecab.parse(sentence).strip().split()
  vec = np.zeros(model.vector_size)
  i = 0
  for word in words:
    try:
      vec = np.add(vec, model[word])
      i += 1
    except:
      pass
    if i == 0: return vec
    return np.divide(vec, i)

for line in sys.stdin:
  m = r.search(line)
  label = m.group(1)
  text = m.group(2)
  print('{0},{1}'.format(label, ','.join(map(str, list(sentenceToVector(text))))))
