<?php

use CodingPaws\PSpec\Assert\Expectation;

describe(Expectation::class, function () {
  let('value', 10);
  subject(fn () => new Expectation(get('value')));

  describe('#toBe', function () {
    it('throws an error if invalid', function () {
      expect(function () {
        subject()->toBe(1);
      })->toThrow(AssertionError::class);
    });

    it('throws no error if valid', function () {
      expect(function () {
        subject()->toBe(10);
      })->toThrow(null);
    });

    describe('arrays', function () {
      subject(new Expectation([1, 2, 3]));

      it('equals', function () {
        subject()->toBe([1, 2, 3]);
      });

      it('not equals', function () {
        expect(function () {
          subject()->toBe([1, 2]);
        })->toThrow(AssertionError::class);
      });
    });

    describe('strings', function () {
      let('value', 'test');

      it('equals', function () {
        subject()->toBe($this->value);
      });

      it('not equals', function () {
        expect(function () {
          subject()->toBe('test');
        })->toThrow(null);
      });
    });
  });

  describe('#toThrow', function () {
    describe('null', function () {
      describe('when the body throws an exception', function () {
        subject(fn () => new Expectation(fn () => 1 / 0));

        it('throws no error', function () {
          expect(function () {
            subject()->toThrow(null);
          })->toThrow(AssertionError::class);
        });
      });

      describe('when the body throws no exception', function () {
        subject(fn () => new Expectation(fn () => 0 / 1));

        it('throws an error', function () {
          subject()->toThrow(null);
        });
      });
    });

    describe('the thrown exception', function () {
      describe('when the body throws an exception', function () {
        subject(fn () => new Expectation(fn () => 1 / 0));

        it('throws no error', function () {
          subject()->toThrow(DivisionByZeroError::class);
        });
      });

      describe('when the body throws no exception', function () {
        subject(fn () => new Expectation(fn () => 0 / 1));

        it('throws an error', function () {
          expect(function () {
            subject()->toThrow(DivisionByZeroError::class);
          })->toThrow(AssertionError::class);
        });
      });
    });

    describe('with a different exception', function () {
      subject(fn () => new Expectation(fn () => 1 / 0));
      it('throws an exception', function () {
        expect(function () {
          subject()->toThrow(RangeException::class);
        })->toThrow(AssertionError::class);
      });
    });
  });

  describe('#toBeCallable', function () {
    subject(new Expectation(fn () => 1));

    it('throws no error if the value is callable', function () {
      expect(function () {
        subject()->toBeCallable();
      })->toThrow(null);
    });

    describe('with a non-callable value', function () {
      subject(new Expectation(1));

      it('throws an error if the value is not callable', function () {
        expect(function () {
          subject()->toBeCallable();
        })->toThrow(AssertionError::class);
      });
    });
  });

  describe('#toContain', function () {
    let('value', 'hello world');

    context('when the value contains the substring', function () {
      it('throws nothing', function () {
        expect(function () {
          subject()->toContain('hello');
          subject()->toContain('world');
        })->toThrow(null);
      });
    });

    context('when the value contains no substring', function () {
      it('throws an error', function () {
        expect(function () {
          subject()->toContain('henlo');
        })->toThrow(AssertionError::class);
      });
    });

    context('with an array', function () {
      let('value', [1,2,3]);

      context('when the value contains the number', function () {
        it('throws nothing', function () {
          expect(function () {
            subject()->toContain(1);
          })->not->toThrow();
        });
      });

      context('when the value doesn’t contain the number', function () {
        it('throws nothing', function () {
          expect(function () {
            subject()->toContain(5);
          })->toThrow(AssertionError::class);
        });
      });
    });
  });
});
