# without venv because of weight limit

import numpy as np
from colorama import Fore, Style


def sig(x: float) -> float:
    """
    Sigmoid function using numpy
    :param x: argument
    :return: value of sigmoid function at argument x
    """
    return 1/(1+np.exp(-x))


def sig_der(x: float) -> float:
    """
    Derivative of sigmoid function
    :param x: argument
    :return: value of sigmoid derivative function at argument x
    """
    return x*(1-x)


def sop(inputs: [float], weights: [float]) -> float:
    """
    Sum of products. Multiplies each input with corresponding weight and sums the results
    :param inputs: array of inputs
    :param weights: array of weights
    :return: sum of products of multiplication between inputs and weights
    """
    if len(inputs) != len(weights):
        raise ValueError("Inputs and weights lists need to be of equal length")
    sum = 0
    for i in range(len(inputs)):
        sum += inputs[i]*weights[i]
    return sum


def error(x_exp: float, x_calc: float) -> float:
    """
    Error function for my perceptron, I chose difference of squares because it is simple
    :param x_exp: expected value
    :param x_calc: calculated value
    :return: square of difference between expected and calculated value
    """
    return (x_calc-x_exp)**2


def error_der(x_exp: float, x_calc: float) -> float:
    """
    derivative of my error function
    :param x_exp: expected value
    :param x_calc: calculated value
    :return: derivative of square of difference between expected and calculated value
    """
    return 2 * (x_calc - x_exp)


def percep_train(inputs: [[]], expected_val: [], eta: float, epochs: int, silent: bool = True) -> [[float]]:
    """
    Function that trains my perceptron using gradient descent.
    :param inputs: list of lists of inputs to the perceptron
    :param expected_val: list of expected values. Should be the same length as inputs
    :param eta: rate of learning
    :param epochs: number of iterations
    :param silent: if true - no output to console. If false will print message with accuracy
    :return: list of weights calculated so that when applied to inputs they approach expected values
    """
    if len(inputs) != len(expected_val):
        raise ValueError("Inputs and values lists need to be of equal length")

    weights = [(np.random.rand()-0.5)/10 for _ in inputs[0]]
    # weights = [0 for _ in inputs[0]]  # 0 initialized weights
    accuracy = []
    for _ in range(epochs):
        random_indexes = [i for i in range(len(inputs))]
        np.random.shuffle(random_indexes)
        avg_error = 0
        for i in random_indexes:
            x = inputs[i]
            predict = sig(sop(x, weights))
            avg_error += error(expected_val[i], predict)
            for j in range(len(weights)):
                weights[j] += eta * error_der(predict, expected_val[i]) * sig_der(predict) * x[j]  # + because of deriv
        accuracy += [1-(avg_error/len(random_indexes))]
    # print(f"{accuracy[0]}\t\t{accuracy[iterations//2]}\t\t{accuracy[-1]}")  #accuracy across epochs, for testing
    if not silent:
        print(f"Training complete. Accuracy after {epochs} epochs: {accuracy[-1]:2.6}%")
    return weights


def compare(margin: float, inputs: [[]], expected_val: [], weights: [float]) -> None:
    """
    Function for comparing expected results with those given by perceptron
    :param margin: margin of when to consider result a pass or fail. Must be between 0 and 1 (lower inclusive)
    :param inputs: inputs of the perceptron
    :param expected_val: expected values for given inputs
    :param weights: weights corresponding to inputs, trained earlier
    :return: None
    """
    if not 0 <= margin < 1:
        raise ValueError("Margin needs to be between 0 and 1 (lower inclusive)")

    for i in range(len(inputs)):
        calc_val = sig(sop(inputs[i], weights))
        if expected_val[i] == 1 and calc_val > margin:
            print(Style.BRIGHT, Fore.GREEN, "PASS -> ", end="")
        elif expected_val[i] == 0 and calc_val < 1 - margin:
            print(Style.BRIGHT, Fore.GREEN, "PASS -> ", end="")
        else:
            print(Style.BRIGHT, Fore.RED, "FAIL -> ", end="")
        print(Style.RESET_ALL, end="")
        print(f"Given input {inputs[i]} perceptron responds with {calc_val:.4f}. Expected value is {expected_val[i]}")


def main():
    logic_gates = {
        "inputs": [[1, 0, 0], [1, 0, 1], [1, 1, 0], [1, 1, 1]],
        "and": [0, 0, 0, 1],
        "or": [0, 1, 1, 1],
        "nand": [1, 1, 1, 0],
        "nor": [1, 0, 0, 0]
    }

    iters = 10000    # <3000 is break off point for 0.7 accuracy
    eta = 0.01

    and_weights = percep_train(logic_gates["inputs"], logic_gates["and"], eta, iters)
    or_weights = percep_train(logic_gates["inputs"], logic_gates["or"], eta, iters)
    nand_weights = percep_train(logic_gates["inputs"], logic_gates["nand"], eta, iters)
    nor_weights = percep_train(logic_gates["inputs"], logic_gates["nor"], eta, iters)

    print(f"Results for training with {iters} iterations with eta = {eta}")
    print(20 * "-=" + "\n\tAND gate:\n"+20*"-=")
    compare(0.7, logic_gates["inputs"], logic_gates["and"], and_weights)
    print(20 * "-=" + "\n\tOR gate:\n"+20*"-=")
    compare(0.7, logic_gates["inputs"], logic_gates["or"], or_weights)
    print(20 * "-=" + "\n\tNAND gate:\n" + 20 * "-=")
    compare(0.7, logic_gates["inputs"], logic_gates["nand"], nand_weights)
    print(20 * "-=" + "\n\tNOR gate:\n" + 20 * "-=")
    compare(0.7, logic_gates["inputs"], logic_gates["nor"], nor_weights)


if __name__ == "__main__":
    main()
